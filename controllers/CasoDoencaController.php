<?php

namespace app\controllers;

use app\components\CRUDController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class CasoDoencaController extends CRUDController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'batch'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'batch'],
                        'roles' => ['Usuario'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'batch' => [
                'class' => 'app\\batch\\Action',
                'modelClass' => 'app\\models\\batch\\CasosDoenca',
            ]
        ];
    }
}
