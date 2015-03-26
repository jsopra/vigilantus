<?php

namespace app\controllers;

use Yii;
use app\models\Equipe;
use app\models\EquipeAgente;
use app\components\DependentCRUDController;

class EquipeAgenteController extends DependentCRUDController
{
    protected $dependentModel = 'Equipe';
    protected $parentField = 'equipe_id';

    private $_equipe;
    private $_agentes;

    public function actionCreate()
    {
        $model = $this->buildNewModel();

        $parentField = $this->parentField;
        $model->$parentField = $this->parentObject->id;

        if (!$this->loadAndSaveModel($model, $_POST, ['index', 'parentID' => $this->dependentID])) {

            $this->_init($model);

            $this->_agentes = EquipeAgente::find()->daEquipe($this->_equipe->id);

            return $this->renderAjaxOrLayout('create', [
                'model' => $model,
                'equipe' => $this->_equipe,
                'agentes' => $this->_agentes
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

            $this->_agentes = EquipeAgente::find()->queNao($model->id)->daEquipe($this->_equipe->id);

            return $this->renderAjaxOrLayout('update', [
                'model' => $model,
                'equipe' => $this->_equipe,
                'agentes' => $this->_agentes
            ]);
        }
    }

    private function _init($model)
    {
        $this->_equipe = $this->parentObject;
    }
}
