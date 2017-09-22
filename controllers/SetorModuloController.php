<?php

namespace app\controllers;

use Yii;
use app\models\Setor;
use app\models\SetorModulo;
use app\components\DependentCRUDController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

class SetorModuloController extends DependentCRUDController
{
    protected $dependentModel = 'Setor';
    protected $parentField = 'setor_id';

    private $_setor;
    private $_modulos;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'delete', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'delete', 'index'],
                        'roles' => ['Root'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = $this->buildNewModel();

        $parentField = $this->parentField;
        $model->$parentField = $this->parentObject->id;

        if (!$this->loadAndSaveModel($model, $_POST, ['index', 'parentID' => $this->dependentID])) {

            $this->_init($model);
            $this->_modulos = SetorModulo::find()->doSetor($this->_setor->id);

            return $this->renderAjaxOrLayout('create', [
                'model' => $model,
                'setores' => $this->_setor,
                'modulos' => $this->_modulos,
            ]);
        }
    }

    private function _init($model) {

        $this->_setor = $this->parentObject;
    }
}