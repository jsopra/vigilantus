<?php

namespace app\controllers;

use Yii;
use yii\db\Expression;
use yii\web\AccessControl;
use app\components\Controller;
use yii\web\VerbFilter;

class FichaRgController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
	public function actionIndex()
	{
		return $this->render('index');
	}
}
