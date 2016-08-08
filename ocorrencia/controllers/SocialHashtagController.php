<?php

namespace app\ocorrencia\controllers;

use app\components\CRUDController;
use yii\filters\AccessControl;

class SocialHashtagController extends CRUDController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'index'],
                        'roles' => ['Gerente'],
                    ],
                ],
            ],
        ];
    }
}
