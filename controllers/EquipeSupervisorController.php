<?php

namespace app\controllers;

use Yii;
use app\models\Equipe;
use app\models\EquipeSupervisor;
use app\components\DependentCRUDController;

class EquipeSupervisorController extends DependentCRUDController
{
    protected $dependentModel = 'Equipe';
    protected $parentField = 'equipe_id';

    private $_equipe;
    private $_supervisores;

    public function actionCreate()
    {
        $model = $this->buildNewModel();

        $parentField = $this->parentField;
        $model->$parentField = $this->parentObject->id;

        if (!$this->loadAndSaveModel($model, $_POST, ['index', 'parentID' => $this->dependentID])) {

            $this->_init($model);

            $this->_supervisores = EquipeSupervisor::find()->daEquipe($this->_equipe->id);

            return $this->renderAjaxOrLayout('create', [
                'model' => $model,
                'equipe' => $this->_equipe,
                'agentes' => $this->_supervisores
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);

        $parentField = $this->parentField;
        $model->$parentField = $this->parentObject->id;

        if (!$this->loadAndSaveModel($model, $_POST, ['index', 'parentID' => $this->dependentID])) {

            $this->_init($model);

            $this->_supervisores = EquipeSupervisor::find()->queNao($model->id)->daEquipe($this->_equipe->id);

            return $this->renderAjaxOrLayout('update', [
                'model' => $model,
                'equipe' => $this->_equipe,
                'agentes' => $this->_supervisores
            ]);
        }
    }

    private function _init($model)
    {
        $this->_equipe = $this->parentObject;
    }
}
