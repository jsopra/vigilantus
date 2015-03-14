<?php

namespace app\controllers;

use app\components\Controller;
use app\models\indicador\ResumoFocosReport;
use app\models\indicador\FocosTipoDepositoReport;
use app\models\indicador\EvolucaoFocosReport;
use app\models\indicador\FocosBairroReport;
use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class IndicadorController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['resumo-focos', 'focos-tipo-deposito', 'evolucao-focos', 'focos-bairro'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['resumo-focos', 'focos-tipo-deposito', 'evolucao-focos', 'focos-bairro'],
                        'roles' => ['Gerente', 'Analista'],
                    ],
                ],
            ],
        ];
    }

    public function actionResumoFocos()
    {
        $model = new ResumoFocosReport;

        $model->load($_GET);
        $model->validate();

        $params = [
            'model' => $model,
            'data' => $model->getData(),
            'dataPercentual' => $model->getDataPercentual(),
        ];

        return $this->render('resumo-focos', $params);
    }

    public function actionFocosTipoDeposito()
    {
        $model = new FocosTipoDepositoReport;


        if(isset($_GET['FocosTipoDepositoReport'])) {
            $model->load($_GET);
            $model->validate();
        }
        else {
            $model->ano = date('Y');
        }

        $params = [
            'model' => $model,
            'data' => $model->getData(),
        ];

        return $this->render('focos-tipos-deposito', $params);
    }

    public function actionEvolucaoFocos()
    {
        $model = new EvolucaoFocosReport;

        $model->load($_GET);
        $model->validate();

        $params = [
            'model' => $model,
            'data' => $model->getData(),
        ];

        return $this->render('evolucao-focos', $params);
    }

    public function actionFocosBairro()
    {
        $model = new FocosBairroReport;


        if(isset($_GET['FocosBairroReport'])) {
            $model->load($_GET);
            $model->validate();
        }
        else {
            $model->ano = date('Y');
        }

        $params = [
            'model' => $model,
            'data' => $model->getData(),
        ];

        return $this->render('focos-bairro', $params);
    }
}
