<?php

namespace app\controllers;

use app\components\Controller;
use app\models\ImovelTipo;
use app\models\search\BoletimRgSearch;

class ResumoRgController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => '\yii\web\AccessControl',
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        $searchModel = new BoletimRgSearch;
        $dataProvider = $searchModel->search($_GET);
        $dataProvider->query->with('boletinsFechamento');
        $dataProvider->query->orderBy('id');
        
        return $this->render(
            'index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'tiposImoveis' => ImovelTipo::find()->all(),
            ]
        );
    }
}