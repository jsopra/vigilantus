<?php

namespace app\controllers;

use app\components\CRUDController;
use app\models\BairroQuarteirao;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

class CasoDoencaController extends CRUDController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'batch', 'bairroQuarteiroes', 'create', 'coordenadasQuarteirao'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'batch', 'bairroQuarteiroes', 'create', 'coordenadasQuarteirao'],
                        'roles' => ['Usuario', 'Analista', 'Tecnico Laboratorial'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'bairroQuarteiroes' => ['class' => 'app\components\actions\BairroQuarteiroes'],
            'batch' => [
                'class' => 'app\\batch\\Action',
                'modelClass' => 'app\\models\\batch\\CasosDoenca',
            ]
        ];
    }

    public function actionCreate()
    {
        $model = $this->buildNewModel();

        if (isset($_POST['CasoDoenca']) && isset($_POST['CasoDoenca']['coordenadasJson'])) {
            $model->coordenadas = explode(',', $_POST['CasosDoenca']['coordenadasJson']);
        }

        if (!$this->loadAndSaveModel($model, $_POST, ['index'])) {

            return $this->renderAjaxOrLayout('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);

        if (isset($_POST['CasoDoenca']) && isset($_POST['CasoDoenca']['coordenadasJson'])) {
            $model->coordenadas = explode(',', $_POST['CasoDoenca']['coordenadasJson']);
        }

        if (!$this->loadAndSaveModel($model, $_POST, ['index'])) {

            return $this->renderAjaxOrLayout('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionCoordenadasQuarteirao($quarteirao_id)
    {
        $quarteirao = BairroQuarteirao::find()->andWhere(['id' => $quarteirao_id])->one();
        if (!$quarteirao) {
            throw new HttpException(404, 'Quarteirão não encontrado.');
        }

        return Json::encode($quarteirao->getCentro());
    }
}
