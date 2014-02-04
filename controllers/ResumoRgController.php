<?php

namespace app\controllers;

use app\components\Controller;
use app\models\ImovelTipo;
use app\models\search\BoletimRgSearch;
use app\models\search\BoletimRgResumoCapaSearch;

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
                        'roles' => ['Administrador'],
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

        $params = [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tiposImoveis' => ImovelTipo::find()->all(),
        ];

        if ($searchModel->bairro_id == null) {
            $params['resumoBairros'] = BoletimRgResumoCapaSearch::porBairros();
            $params['resumoTiposImoveis'] = BoletimRgResumoCapaSearch::porTiposDeImoveis();
        }
        
        return $this->render(
            'index',
            $params
        );
    }
}