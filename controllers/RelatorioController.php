<?php

namespace app\controllers;

use app\components\Controller;
use app\models\report\ResumoRgBairroReport;
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
                'only' => ['resumo-rg-bairro'],
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
}
