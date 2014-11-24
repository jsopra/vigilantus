<?php

namespace app\modules\denuncia\controllers;

use app\components\CRUDController;

use Yii;
use yii\filters\AccessControl;

class DenunciaTipoProblemaController extends CRUDController
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