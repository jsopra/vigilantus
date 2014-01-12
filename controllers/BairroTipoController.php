<?php

namespace app\controllers;

use app\components\ActiveRecord;
use app\components\CRUDController;
use Yii;

/**
 * BairroTipoController implements the CRUD actions for BairroTipo model.
 */
class BairroTipoController extends CRUDController
{
    /**
     * @inheritdoc
     */
    protected function loadAndSaveModel(ActiveRecord $model, $data = null)
    {
        if ($model->load($_POST)) {
            
            if ($model->isNewRecord) {
                $model->inserido_por = Yii::$app->user->identity->id;
            } else {
                $model->atualizado_por = Yii::$app->user->identity->id;
            }
            
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
    }
}
