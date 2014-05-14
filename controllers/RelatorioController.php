<?php

namespace app\controllers;

use app\components\Controller;
use app\models\report\ResumoRgBairroReport;
use app\models\report\AreaTratamentoReport;
use app\models\search\FocoTransmissorSearch;
use app\models\BairroQuarteirao;
use app\models\FocoTransmissor;
use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

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
    
    public function actionAreaTratamento()
    {
        $model = new AreaTratamentoReport;
        
        $model->load($_GET);

        return $this->render('area-tratamento', ['model' => $model]);
    }
    
    public function actionAreaTratamentoFocos($idQuarteirao) {
            
        $quarteirao = BairroQuarteirao::findOne($idQuarteirao);
        
        $dataProvider = FocoTransmissor::find()->daAreaDeTratamento($quarteirao);

        return $this->renderPartial(
            '_detalhamento-areas-tratamento',
            ['dataProvider' => new ActiveDataProvider(['query' => $dataProvider])]
        );
    }
}
