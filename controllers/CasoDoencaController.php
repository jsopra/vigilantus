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
                'only' => ['index', 'batch', 'bairroQuarteiroes', 'create'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'batch', 'bairroQuarteiroes', 'create'],
                        'roles' => ['Usuario', 'Analista', 'Tecnico Laboratorial'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'bairroQuarteiroes' => ['class' => 'app\components\actions\BairroQuarteiroes'],
            'batch' => [
                'class' => 'app\\batch\\Action',
                'modelClass' => 'app\\models\\batch\\CasosDoenca',
            ]
        ];
    }
}
