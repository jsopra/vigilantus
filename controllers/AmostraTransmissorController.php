<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\CRUDController;
use app\batch\controller\Batchable;
use app\models\AmostraTransmissor;


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
                        'roles' => ['Usuario'],
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
