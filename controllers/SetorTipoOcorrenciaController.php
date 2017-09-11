<?php

namespace app\controllers;

use Yii;
use app\models\Setor;
use app\models\SetorTipoOcorrencia;
use app\models\search\SetorTipoOcorrenciaSearch;
use app\components\DependentCRUDController;

class SetorTipoOcorrenciaController extends DependentCRUDController
{
    protected $dependentModel = 'Setor';
    protected $parentField = 'setor_id';

    private $_setor;
    private $_setores;

    public function actionCreate()
    {
        $model = $this->buildNewModel();

        $parentField = $this->parentField;
        $model->$parentField = $this->parentObject->id;

        if (!$this->loadAndSaveModel($model, $_POST, ['index', 'parentID' => $this->dependentID])) {

            $this->_init($model);

            $this->_setores = SetorTipoOcorrencia::find()->doSetor($this->_setor->id);

            return $this->renderAjaxOrLayout('create', [
                'model' => $model,
                'setor' => $this->_setor,
                'setores' => $this->_setores,
            ]);
        }
    }

    private function _init($model)
    {
        $this->_setor = $this->parentObject;
    }
}
