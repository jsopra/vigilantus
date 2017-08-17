<?php

namespace app\ocorrencia\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\CRUDController;
use app\helpers\models\OcorrenciaHelper;
use app\models\OcorrenciaHistorico;
use app\models\OcorrenciaStatus;
use yii\data\ActiveDataProvider;
use app\models\Ocorrencia;
use app\forms\OcorrenciaImpressaoForm;
use yii\web\UploadedFile;
use app\models\search\OcorrenciaHistoricoSearch;
use app\models\OcorrenciaHistoricoTipo;

class OcorrenciaController extends CRUDController
{
    public function actions()
    {
        return [
            'imoveis' => ['class' => 'app\components\actions\Imoveis'],
            'bairroQuarteiroes' => ['class' => 'app\components\actions\BairroQuarteiroes'],
            'batch' => [
                'class' => 'app\\batch\\Action',
                'modelClass' => 'app\\models\\batch\\Ocorrencia',
            ]
        ];
    }

     /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'index', 'anexo', 'reprovar', 'aprovar', 'detalhes', 'imoveis', 'mudar-status', 'mudar-setor', 'bairroQuarteiroes', 'tentativa-averiguacao', 'comprovante', 'ver-averiguacoes', 'batch', 'abertas', 'impressao'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'index', 'anexo', 'reprovar', 'aprovar', 'detalhes', 'imoveis', 'mudar-status', 'bairroQuarteiroes', 'mudar-setor', 'tentativa-averiguacao', 'comprovante', 'ver-averiguacoes', 'batch', 'abertas', 'impressao'],
                        'roles' => ['Usuario'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModelClass = $this->getSearchModelClassName();
        $searchModel = new $searchModelClass;
        $searchModel->cliente_id = Yii::$app->user->identity->cliente_id;
        $dataProvider = $searchModel->search($_GET);

        Yii::$app->user->returnUrl = Yii::$app->urlManager->createUrl('ocorrencia/ocorrencia/index');

        return $this->renderAjaxOrLayout(
            'index',
            ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]
        );
    }

    public function actionAbertas()
    {
        $searchModelClass = $this->getSearchModelClassName();
        $searchModel = new $searchModelClass;

        $_GET['OcorrenciaSearch']['data_fechamento'] = '0';
        $_GET['OcorrenciaSearch']['status_fechamento'] = null;

        $dataProvider = $searchModel->search($_GET);

        Yii::$app->user->returnUrl = Yii::$app->urlManager->createUrl('ocorrencia/ocorrencia/abertas');

        return $this->renderAjaxOrLayout(
            'abertas',
            ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]
        );
    }

    public function actionCreate()
    {
        $model = new Ocorrencia();

        if (Yii::$app->request->post()) {

            $model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');

            if($model->validate()) {

                if($model->file) {
                    $model->nome_original_anexo = $model->file->baseName . '.' . $model->file->extension;
                    $model->anexo = time() . '.' . $model->file->extension;
                }

                $model->scenario = 'aprovacao';
                $model->status = OcorrenciaStatus::APROVADA;
                $model->usuario_id = Yii::$app->user->id;

                if ($model->save()) {

                    if($model->file) {

                        $s3 = Yii::$app->get('s3');

                        $pathToFile = getenv('UPLOADS_DIR') . $model->anexo;

                        if (!$model->file->saveAs($pathToFile, false)) {
                            throw new \Exception('Erro ao salvar arquivo em disco');
                        }

                        if (!$s3->put('ocorrencias/' . $model->anexo, file_get_contents($pathToFile))) {
                            throw new \Exception('Erro ao salvar arquivo original no S3');
                        }

                        if (is_file($pathToFile)) {
                            unset($pathToFile);
                        }
                    }

                    Yii::$app->session->setFlash('success', 'Ocorrência cadastrada com sucesso.');

                    return $this->redirect(Yii::$app->user->returnUrl);
                }
                else {
                    Yii::$app->session->setFlash('error', 'Erro ao salvar a ocorrência.');
                }
            }
        }
        $model->data_criacao = date('Y-m-d');
         return $this->render(
            'create',
            [
                'model' => $model,
            ]
        );
    }

    public function actionAnexo($id)
    {
    	$model = is_object($id) ? $id : $this->findModel($id);

        $this->redirect(Yii::$app->get('s3')->getUrl('ocorrencias/' . $model->anexo));
    }

    public function actionReprovar($id)
    {
        $ocorrencia = is_object($id) ? $id : $this->findModel($id);

        $model = new \app\forms\OcorrenciaRejeicaoForm;
        $model->ocorrencia_id = $ocorrencia->id;
        $model->usuario_id = Yii::$app->user->id;

        if (!empty($_POST) && $model->load($_POST)) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Ocorrência reprovada!');
                return $this->redirect(Yii::$app->user->returnUrl);
            }
        }

        return $this->renderAjaxOrLayout('reprovar', [
            'model' => $ocorrencia,
            'modelForm' => $model
        ]);
    }

    public function actionAprovar($id)
    {
    	$model = is_object($id) ? $id : $this->findModel($id);

    	if (!empty($_POST) && $model->load($_POST)) {

    		$model->scenario = 'aprovacao';
	    	$model->status = OcorrenciaStatus::APROVADA;
	    	$model->usuario_id = Yii::$app->user->id;

    		$saveMethodName = $this->getModelSaveMethodName();

    		if ($model->$saveMethodName()) {

                Yii::$app->session->setFlash('success', 'Ocorrência aprovada!');

                return $this->redirect(Yii::$app->user->returnUrl);
            }
        }

        return $this->renderAjaxOrLayout('aprovar', ['model' => $model]);
    }

    public function actionDetalhes($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);

        $dataProvider = new ActiveDataProvider([
            'query' => OcorrenciaHistorico::find()->daOcorrencia($model->id),
        ]);

        return $this->render(
            'detalhes',
            [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'historicos' => $model->getOcorrenciaHistoricos()->all(),
            ]
        );
    }

    public function actionMudarStatus($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);

        if (!empty($_POST) && $model->load($_POST)) {

            $model->scenario = Ocorrencia::SCENARIO_TROCA_STATUS;
            $model->usuario_id = Yii::$app->user->id;
            $model->status = $_POST['Ocorrencia']['status'];

            $saveMethodName = $this->getModelSaveMethodName();

            if ($model->$saveMethodName()) {
                Yii::$app->session->setFlash('success', 'Ocorrência teve status alterado!');
                return $this->redirect(Yii::$app->user->returnUrl);
            }
            Yii::$app->session->setFlash('error', 'Ocorreu um erro ao alterar o status da ocorrência.');
        }

        return $this->renderAjaxOrLayout('mudar-status', ['model' => $model]);
    }

    public function actionMudarSetor($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);
        if (!empty($_POST) && $model->load($_POST)) {

            $model->scenario = Ocorrencia::SCENARIO_TROCA_SETOR;
            $model->usuario_id = Yii::$app->user->id;
            $model->setor_id = $_POST['Ocorrencia']['setor_id'];

            $saveMethodName = $this->getModelSaveMethodName();

            if ($model->$saveMethodName()) {
                Yii::$app->session->setFlash('success', 'Ocorrência teve setor alterado!');
                return $this->redirect(Yii::$app->user->returnUrl);
            } 
            Yii::$app->session->setFlash('error', 'Ocorreu um erro ao alterar o setor da ocorrência.');
        }
        return $this->renderAjaxOrLayout('mudar-setor', ['model' => $model]);
    }

    public function actionTentativaAveriguacao($id)
    {
        $ocorrencia = is_object($id) ? $id : $this->findModel($id);

        $model = new \app\forms\TentativaAveriguacaoForm;

        if (!empty($_POST) && $model->load($_POST)) {

            $model->ocorrencia_id = $ocorrencia->id;
            $model->cliente_id = $ocorrencia->cliente_id;
            $model->usuario_id = Yii::$app->user->id;

            if ($model->save()) {

                if($model->fechou_visita) {
                    Yii::$app->session->setFlash('success', 'Ocorrência teve tentativa de visita registrada e foi fechada após exceder o limite de tentativas de visitação');
                } else {
                    Yii::$app->session->setFlash('success', 'Ocorrência teve tentativa de visita registrada.');
                }

                return $this->redirect(Yii::$app->user->returnUrl);
            }
        }

        return $this->renderAjaxOrLayout('tentativa-visita', ['model' => $ocorrencia, 'modelForm' => $model]);
    }

    public function actionComprovante($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);

        Yii::$app->response->format = 'pdf';
        $this->layout = '//print';
        return $this->render('//shared/comprovante-ocorrencia', [
            'model' => $model,
        ]);
    }

    public function actionVerAveriguacoes($id)
    {
        $searchModel = new OcorrenciaHistoricoSearch;

        $dataProvider = $searchModel->search(['ocorrencia_id' => $id, 'tipo' => OcorrenciaHistoricoTipo::AVERIGUACAO]);

        return $this->renderPartial(
            '_averiguacoes',
            ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]
        );
    }

    public function actionImpressao($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);$searchModel = new OcorrenciaHistoricoSearch;

        $dataProvider = $searchModel->search(['ocorrencia_id' => $id]);

        $this->layout = '//printweb';
        return $this->render('_print', [
            'model' => $model, 'dataProvider' => $dataProvider
        ]);
    }
}
