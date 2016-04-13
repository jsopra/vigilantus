<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\CRUDController;
use app\batch\controller\Batchable;
use app\models\AmostraTransmissor;
use Yii;


class AmostraTransmissorController extends CRUDController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'delete', 'index', 'update'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'index'],
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
    public function actionView($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);

        if (!$this->loadAndSaveModelAnalise($model, $_POST)) {
            return $this->renderAjaxOrLayout('view', ['model' => $model]);
        }
    }
    protected function loadAndSaveModelAnalise($model, $data = null)
    {
        if (!empty($data) && $model->load($data)) {

            if ($model->hasAttribute('atualizado_por')) {
                $model->atualizado_por = Yii::$app->user->identity->id;
            }

            $saveMethodName = $this->getModelSaveMethodName();

            if ($model->$saveMethodName()) {

                Yii::$app->session->setFlash('success', 'Análise Laboratorial salva com sucesso!');

                return $this->redirect(['index']);
            }
        }

        return false;
    }
}
