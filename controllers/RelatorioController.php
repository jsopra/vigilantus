<?php

namespace app\controllers;

use app\components\Controller;
use app\models\report\ResumoRgBairroReport;
use app\models\report\MapaAreaTratamentoReport;
use Yii;
use yii\filters\AccessControl;

class RelatorioController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['resumo-rg-bairro', 'mapa-area-tratamento'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['Administrador'],
                    ],
                ],
            ],
        ];
    }

    public function actionResumoRgBairro()
    {
        $model = new ResumoRgBairroReport;
        $params = [
            'model' => $model,
            'solicitado' => false,
        ];

        if ($model->load($_GET) && $model->validate()) {
            $params['solicitado'] = true;
        }

        return $this->render('resumo-rg-bairro', $params);
    }
    
    public function actionMapaAreaTratamento()
    {
        $model = new MapaAreaTratamentoReport;
        
        $model->load($_GET);

        return $this->render('mapa-area-tratamento', ['model' => $model]);
    }
}
