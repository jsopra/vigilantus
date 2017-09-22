<?php

namespace app\ocorrencia\controllers;

use app\components\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use app\models\indicador\OcorrenciasMesReport;
use app\models\indicador\OcorrenciasProblemaReport;
use app\models\indicador\OcorrenciasStatusReport;

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
                'only' => ['ocorrencias-mes', 'ocorrencias-problema', 'ocorrencias-status'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['ocorrencias-mes', 'ocorrencias-problema', 'ocorrencias-status'],
                        'roles' => ['Gerente', 'Analista'],
                    ],
                ],
            ],
        ];
    }

    public function actionOcorrenciasMes()
    {
        $model = new OcorrenciasMesReport;
        $model->usuario = Yii::$app->user->identity;

        if(isset($_GET['OcorrenciasMesReport'])) {
            $model->load($_GET);
            $model->validate();
        }
        else {
            $model->ano = date('Y');
        }

        $params = [
            'model' => $model,
        ];

        return $this->render('ocorrencias-mes', $params);
    }

    public function actionOcorrenciasProblema()
    {
        $model = new OcorrenciasProblemaReport;

        if(isset($_GET['OcorrenciasProblemaReport'])) {
            $model->load($_GET);
            $model->validate();
        }
        else {
            $model->ano = date('Y');
        }

        $params = [
            'model' => $model,
        ];

        return $this->render('ocorrencias-problema', $params);
    }

    public function actionOcorrenciasStatus()
    {
        $model = new OcorrenciasStatusReport;

        if(isset($_GET['OcorrenciasStatusReport'])) {
            $model->load($_GET);
            $model->validate();
        }
        else {
            $model->ano = date('Y');
        }

        $params = [
            'model' => $model,
        ];

        return $this->render('ocorrencias-status', $params);
    }
}
