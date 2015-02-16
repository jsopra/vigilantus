<?php

namespace app\controllers;

use app\components\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use app\models\map\TratamentoFocoMapForm;

class MapaController extends Controller
{
    public function actions()
    {
        return [
            'focos' => ['class' => 'app\components\actions\Focos'],
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['tratamento-foco', 'focos'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['tratamento-foco', 'visao-geral'],
                        'roles' => ['Gerente'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['Usuario'],
                    ],
                ],
            ],
        ];
    }

    public function actionTratamentoFoco()
    {
        $model = new TratamentoFocoMapForm;

        if (!empty($_REQUEST)) {
            $model->load($_REQUEST);
        }

        $foco = $model->foco_id ? \app\models\FocoTransmissor::find()->where(['id' => $model->foco_id])->one() : null;

        return $this->render('tratamento-foco', [
            'model' => $model,
            'foco' => $foco,
        ]);
    }

    public function actionVisaoGeral()
    {
        return $this->render('visao-geral', [

        ]);
    }
}
