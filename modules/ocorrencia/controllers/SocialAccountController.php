<?php

namespace app\modules\ocorrencia\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\Controller;

class SocialAccountController extends Controller
{
     /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['Usuario'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index',[
            'cliente' => Yii::$app->user->identity->cliente
        ]);
    }
}
