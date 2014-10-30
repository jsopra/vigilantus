<?php

namespace app\controllers;

use app\components\Controller;
use app\models\report\ResumoRgBairroReport;
use app\models\report\AreaTratamentoReport;
use app\models\report\FocosExcelReport;
use app\models\search\FocoTransmissorSearch;
use app\models\BairroQuarteirao;
use app\models\FocoTransmissor;
use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use app\extensions\geom\kml\Kml;
use app\extensions\geom\kml\models\Polygon;
use app\extensions\geom\kml\models\Point;

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
                'only' => ['resumo-rg-bairro', 'focos-area-tratamento', 'area-tratamento', 'area-tratamento-focos', 'area-tratamento-mapa', 'focos-export'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['resumo-rg-bairro', 'focos-area-tratamento', 'area-tratamento', 'area-tratamento-focos', 'area-tratamento-mapa', 'resumo-rg-bairro', 'mapa-area-tratamento'],
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

    public function actionFocosKml()
    {
        $model = new Kml;
        
        $focos = FocoTransmissor::find()->ativo()->all();
        foreach($focos as $foco) {
                    
            /*
             * Quarteirão
             */
            $quarteirao = $foco->bairroQuarteirao;
            $quarteirao->loadCoordenadas();
            
            $polygon = new Polygon;
            
            foreach($quarteirao->coordenadas as $coordenada) {
                $point = new Point;
                $point->value = $coordenada;
                $polygon->value[] = $point;
                unset($point);
            }
            
            $model->add($polygon);
            unset($polygon);
            
            /*
             * Foco
             */
            $centro = $quarteirao->getCentro();
            
            $polygon = new Polygon;
            
            foreach($quarteirao->coordenadas as $coordenada) {
                $point = new Point;
                $point->value = $coordenada;
                $polygon->value[] = $point;
                unset($point);
            }
            
            $model->add($polygon);
            unset($polygon);
        }    

        return $model->toJSON();
    }
}
