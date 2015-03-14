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

use app\models\redis\Queue;

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
                'only' => ['resumo-rg-bairro', 'focos-area-tratamento', 'area-tratamento', 'area-tratamento-focos', 'area-tratamento-mapa', 'focos-export', 'focos', 'focos-bairro', 'focos-bairro-data', 'download-mapa', 'update-rg'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['resumo-rg-bairro', 'focos-area-tratamento', 'area-tratamento', 'area-tratamento-focos', 'area-tratamento-mapa', 'resumo-rg-bairro', 'mapa-area-tratamento', 'focos', 'focos-bairro', 'focos-bairro-data', 'download-mapa', 'update-rg'],
                        'roles' => ['Gerente', 'Analista'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['focos-export', 'update-rg'],
                        'roles' => ['Usuario', 'Analista'],
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

        $model->loadAreasDeTratamento(\Yii::$app->session->get('user.cliente'));

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
            'url' => $model->getUrlAreasFocos(),
        ]);
    }

    public function actionFocosAreaTratamento($idQuarteirao) {

        $quarteirao = BairroQuarteirao::findOne($idQuarteirao);

        $dataProvider = FocoTransmissor::find()->daAreaDeTratamento($quarteirao);

        $this->layout = 'ajax';

        return $this->render(
            '_detalhamento-areas-tratamento',
            ['dataProvider' => new ActiveDataProvider(['query' => $dataProvider, 'pagination' => false])]
        );
    }

    public function actionFocosExport()
    {
        $model = new FocosExcelReport;

        if ($model->load($_GET) && $model->validate()) {
            $model->export(\Yii::$app->session->get('user.cliente'));
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

        $this->layout = 'ajax';

        return $this->render(
            '_detalhamento-focos-bairro-data',
            ['dataProvider' => new ActiveDataProvider(['query' => $dataProvider, 'pagination' => false])]
        );
    }

    public function actionDownloadMapa($bairro_id = null, $lira = null, $especie_transmissor_id = null) {

        $model = new AreaTratamentoReport;

        $model->bairro_id = $bairro_id;
        $model->lira = $lira;
        $model->especie_transmissor_id = $especie_transmissor_id;

        $this->layout = 'ajax';

        return $this->render('mapa_impressao', [
            'model' => $model,
            'url' => $model->getUrlAreasFocos(),
        ]);
    }

    public function actionUpdateRg()
    {
        Queue::push('RefreshResumoFechamentoRgJob');

        Yii::$app->session->setFlash('success', 'Em até 10 minutos o relatório estará atualizado.');

        return $this->redirect(['site/home']);
    }
}
