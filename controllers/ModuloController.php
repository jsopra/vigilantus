<?php

namespace app\controllers;

use app\components\CRUDController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

class ModuloController extends CRUDController
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
                        'roles' => ['Root'],
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
}