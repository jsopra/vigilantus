<?php

namespace app\controllers;

use app\components\CRUDController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\SemanaEpidemiologica;

class SemanaEpidemiologicaController extends CRUDController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'delete', 'index', 'update'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'index', 'agendar'],
                        'roles' => ['Usuario', 'Supervisor'],
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

    public function actionAgendar($id)
    {
        $model = $this->findModel($id);
        
        return $this->renderAjaxOrLayout('agendar', [
            'model' => $model
        ]);
    }
}
