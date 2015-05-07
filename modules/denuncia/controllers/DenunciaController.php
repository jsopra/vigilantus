<?php

namespace app\modules\denuncia\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\CRUDController;
use app\helpers\models\DenunciaHelper;
use app\models\DenunciaHistorico;
use app\models\DenunciaStatus;
use yii\data\ActiveDataProvider;
use app\models\Denuncia;
use yii\web\UploadedFile;

class DenunciaController extends CRUDController
{
    public function actions()
    {
        return [
            'imoveis' => ['class' => 'app\components\actions\Imoveis'],
            'bairroQuarteiroes' => ['class' => 'app\components\actions\BairroQuarteiroes'],
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
                'only' => ['create', 'index', 'anexo', 'reprovar', 'aprovar', 'detalhes', 'imoveis', 'mudar-status', 'bairroQuarteiroes', 'tentativa-averiguacao'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'index', 'anexo', 'reprovar', 'aprovar', 'detalhes', 'imoveis', 'mudar-status', 'bairroQuarteiroes', 'tentativa-averiguacao'],
                        'roles' => ['Usuario'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = new Denuncia();

        if (Yii::$app->request->post()) {

            $model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');

            if($model->validate()) {

                if($model->file) {
                    $model->nome_original_anexo = $model->file->baseName . '.' . $model->file->extension;
                    $model->anexo = time() . '.' . $model->file->extension;
                }

                $model->scenario = 'aprovacao';
                $model->status = DenunciaStatus::APROVADA;
                $model->usuario_id = Yii::$app->user->id;

                if ($model->save()) {

                    if($model->file) {
                        $model->file->saveAs(DenunciaHelper::getUploadPath() . $model->anexo);
                    }

                    Yii::$app->session->setFlash('success', 'Denúncia cadastrada com sucesso.');

                    return $this->redirect(['denuncia/index']);
                }
                else {
                    Yii::$app->session->setFlash('error', 'Erro ao salvar a denúncia.');
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

    	$response->sendFile(DenunciaHelper::getUploadPath() . $model->anexo, $model->nome_original_anexo);

    	$response->send();
    }

    public function actionReprovar($id)
    {
    	$model = is_object($id) ? $id : $this->findModel($id);

    	$model->scenario = 'trocaStatus';
    	$model->status = DenunciaStatus::REPROVADA;
    	$model->usuario_id = Yii::$app->user->id;

    	if($model->save()) {
    		Yii::$app->session->setFlash('success', 'Atualização executada com sucesso');
    	}
    	else {
    		Yii::$app->session->setFlash('error', 'Erro ao atualizar status do processo');
    	}

    	$this->redirect(['denuncia/index']);
    }

    public function actionAprovar($id)
    {
    	$model = is_object($id) ? $id : $this->findModel($id);

    	if (!empty($_POST) && $model->load($_POST)) {

    		$model->scenario = 'aprovacao';
	    	$model->status = DenunciaStatus::APROVADA;
	    	$model->usuario_id = Yii::$app->user->id;

    		$saveMethodName = $this->getModelSaveMethodName();

    		if ($model->$saveMethodName()) {

                Yii::$app->session->setFlash('success', 'Denúncia aprovada!');

                return $this->redirect(['denuncia/index']);
            }
        }

        return $this->renderAjaxOrLayout('aprovar', ['model' => $model]);
    }

    public function actionDetalhes($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);

        $dataProvider = new ActiveDataProvider([
            'query' => DenunciaHistorico::find()->daDenuncia($model->id),
        ]);

        echo $this->render('detalhes', ['model' => $model, 'dataProvider' => $dataProvider]);
    }

    public function actionMudarStatus($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);

        if (!empty($_POST) && $model->load($_POST)) {

            $model->scenario = 'trocaStatus';
            $model->usuario_id = Yii::$app->user->id;

            $saveMethodName = $this->getModelSaveMethodName();

            if ($model->$saveMethodName()) {

                Yii::$app->session->setFlash('success', 'Denúncia teve status alterado!');

                return $this->redirect(['denuncia/index']);
            }
        }

        return $this->renderAjaxOrLayout('mudar-status', ['model' => $model]);
    }

    public function actionTentativaAveriguacao($id)
    {
        $denuncia = is_object($id) ? $id : $this->findModel($id);

        $model = new \app\forms\TentativaAveriguacaoForm;

        if (!empty($_POST) && $model->load($_POST)) {

            $model->denuncia_id = $denuncia->id;
            $model->cliente_id = $denuncia->cliente_id;
            $model->usuario_id = Yii::$app->user->id;

            if ($model->save()) {

                if($model->fechou_visita) {
                    Yii::$app->session->setFlash('success', 'Denúncia teve tentativa de visita registrada e foi fechada após exceder o limite de tentativas de visitação');
                } else {
                    Yii::$app->session->setFlash('success', 'Denúncia teve tentativa de visita registrada.');
                }

                return $this->redirect(['denuncia/index']);
            }
        }

        return $this->renderAjaxOrLayout('tentativa-visita', ['model' => $denuncia, 'modelForm' => $model]);
    }
}
