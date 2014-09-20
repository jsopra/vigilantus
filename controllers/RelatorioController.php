<?php

namespace app\controllers;

use app\components\Controller;
use app\models\report\ResumoRgBairroReport;
use app\models\report\AreaTratamentoReport;
use app\models\report\FocosReport;
use app\models\report\FocosExcelReport;
use app\models\report\FocosBairroReport;
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
                'only' => ['resumo-rg-bairro', 'focos-area-tratamento', 'area-tratamento', 'area-tratamento-focos', 'area-tratamento-mapa', 'focos-export', 'focos', 'focos-bairro', 'focos-bairro-data'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['resumo-rg-bairro', 'focos-area-tratamento', 'area-tratamento', 'area-tratamento-focos', 'area-tratamento-mapa', 'resumo-rg-bairro', 'mapa-area-tratamento', 'focos', 'focos-bairro', 'focos-bairro-data'],
                        'roles' => ['Gerente'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['focos-export'],
                        'roles' => ['Usuario'],
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
        
        $model->loadAreasDeTratamento();

        return $this->render('area-tratamento', ['model' => $model]);
    }
    
    public function actionAreaTratamentoFocos()
    {
        $model = new AreaTratamentoReport;
        
        $model->load($_GET);

        $model->loadAreasDeFoco();

        return $this->render('focos', ['model' => $model]);
    }
    
    public function actionAreaTratamentoMapa()
    {
        $model = new AreaTratamentoReport;
        
        $model->load($_GET);

        return $this->render('mapa', [
            'model' => $model,
            'modelFocos' => $model->loadAreasDeFocoMapa()
        ]);
    }
    
    public function actionFocosAreaTratamento($idQuarteirao) {
            
        $quarteirao = BairroQuarteirao::findOne($idQuarteirao);
        
        $dataProvider = FocoTransmissor::find()->daAreaDeTratamento($quarteirao);

        return $this->renderPartial(
            '_detalhamento-areas-tratamento',
            ['dataProvider' => new ActiveDataProvider(['query' => $dataProvider])]
        );
    }
    
    public function actionFocosExport()
    {
        $model = new FocosExcelReport;
        
        if ($model->load($_GET) && $model->validate()) {
            $model->export();
        }

        return $this->render('focos-export', ['model' => $model]);
    }

    public function actionFocos()
    {
        $model = new FocosReport;
        
        if(!isset($_GET['FocosReport'])) {
            $model->ano = date('Y');
        }

        $model->load($_GET);
        
        return $this->render('relatorio-focos', ['model' => $model]);
    }

    public function actionFocosBairro()
    {
        $model = new FocosBairroReport;
        
        if(!isset($_GET['FocosBairroReport'])) {
            $model->ano = date('Y');
        }

        $model->load($_GET);
        
        return $this->render('relatorio-focos-bairro', ['model' => $model]);
    }

    public function actionFocosBairroData($idBairro, $ano, $mes = null, $idEspecieTransmissor = null) 
    {
        $dataProvider = FocoTransmissor::find()->doBairro($idBairro)->doAno($ano);

        if($idEspecieTransmissor) {
            $dataProvider->daEspecieTransmissor($idEspecieTransmissor);
        }

        if($mes) {
            $dataProvider->doMes($mes);
        }

        return $this->renderPartial(
            '_detalhamento-focos-bairro-data',
            ['dataProvider' => new ActiveDataProvider(['query' => $dataProvider, 'pagination' => false])]
        );
    }
}
