<?php

namespace app\modules\ocorrencia\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\CRUDController;
use app\helpers\models\OcorrenciaHelper;
use app\models\OcorrenciaHistorico;
use app\models\OcorrenciaStatus;
use yii\data\ActiveDataProvider;
use app\models\Ocorrencia;
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
                'only' => ['create', 'index', 'anexo', 'reprovar', 'aprovar', 'detalhes', 'imoveis', 'mudar-status', 'bairroQuarteiroes', 'tentativa-averiguacao', 'comprovante', 'ver-averiguacoes', 'batch', 'reprovar'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'index', 'anexo', 'reprovar', 'aprovar', 'detalhes', 'imoveis', 'mudar-status', 'bairroQuarteiroes', 'tentativa-averiguacao', 'comprovante', 'ver-averiguacoes', 'batch', 'reprovar'],
                        'roles' => ['Usuario'],
                    ],
                ],
            ],
        ];
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
                        $model->file->saveAs(OcorrenciaHelper::getUploadPath() . $model->anexo);
                    }

                    Yii::$app->session->setFlash('success', 'Ocorrência cadastrada com sucesso.');

                    return $this->redirect(['ocorrencia/index']);
                }
                else {
                    Yii::$app->session->setFlash('error', 'Erro ao salvar a ocorrência.');
                }
            }
        }

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

    	$response = new yii\web\Response;

    	$response->sendFile(OcorrenciaHelper::getUploadPath() . $model->anexo, $model->nome_original_anexo);

    	$response->send();
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
                return $this->redirect(['ocorrencia/index']);
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

                return $this->redirect(['ocorrencia/index']);
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

        echo $this->render('detalhes', ['model' => $model, 'dataProvider' => $dataProvider]);
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

                return $this->redirect(['ocorrencia/index']);
            }
        }

        return $this->renderAjaxOrLayout('mudar-status', ['model' => $model]);
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

                return $this->redirect(['ocorrencia/index']);
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
}
