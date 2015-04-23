<?php

namespace app\controllers;

use app\components\CRUDController;
use app\helpers\GoogleMapsAPIHelper;

class BairroController extends CRUDController
{
    public function actionUpdate($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);

        if (!$this->loadAndSaveModel($model, $_POST, ['index'])) {

            $model->loadCoordenadas();
            if(!$model->getIsNewRecord() && !$model->coordenadasJson && $model->coordenadas) {
                $model->coordenadasJson = GoogleMapsAPIHelper::arrayToCoordinatesJson($model->coordenadas);
            }

            return $this->renderAjaxOrLayout('update', [
                'model' => $model,
            ]);
        }
    }
}
