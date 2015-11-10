<?php

namespace app\controllers;

use app\components\CRUDController;
use app\helpers\MapHelper;

class BairroController extends CRUDController
{

    public function actionCreate()
    {
        $model = $this->buildNewModel();

        if (!$this->loadAndSaveModel($model, $_POST, ['index'])) {

            if ($model->coordenadasJson && !$model->coordenadas) {
                $model->coordenadas = MapHelper::jsonToCoordinatesArray($model->coordenadasJson);
            }

            return $this->renderAjaxOrLayout('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);

        if (!$this->loadAndSaveModel($model, $_POST, ['index'])) {

            $model->loadCoordenadas();
            if (!$model->getIsNewRecord() && !$model->coordenadasJson && $model->coordenadas) {
                $model->coordenadasJson = MapHelper::getArrayCoordenadas($model->coordenadas);
            } else if($model->coordenadasJson) {
                $model->coordenadas = MapHelper::jsonToCoordinatesArray($model->coordenadasJson);
            }

            return $this->renderAjaxOrLayout('update', [
                'model' => $model,
            ]);
        }
    }
}
