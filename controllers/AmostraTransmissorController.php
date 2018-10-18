<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\CRUDController;
use app\batch\controller\Batchable;
use app\models\AmostraTransmissor;
use app\models\VisitaImovel;
use Yii;


class AmostraTransmissorController extends CRUDController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'visita'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'visita'],
                        'roles' => ['Usuario','Tecnico Laboratorial'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['Administrador', 'Tecnico Laboratorial'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionVisita($id)
    {
        $visita = VisitaImovel::find()->one();
        if ($visita === null) {
           throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->renderPartial(
            '_visita',
            ['model' => $visita]
        );
    }

    public function actionView($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);

        if (!$this->loadAndSaveModelAnalise($model, $_POST)) {

            return $this->renderAjaxOrLayout('view', [
                'model' => $model,
                'submitting' => isset($_POST['AmostraTransmissor']),
            ]);
        }
    }
    protected function loadAndSaveModelAnalise($model, $data = null)
    {
        if (!empty($data) && $model->load($data)) {

            $model->atualizado_por = Yii::$app->user->identity->id;

            $saveMethodName = $this->getModelSaveMethodName();

            if ($model->$saveMethodName()) {

                Yii::$app->session->setFlash('success', 'Análise Laboratorial salva com sucesso!');

                return $this->redirect(['index']);
            }
        }

        return false;
    }
}