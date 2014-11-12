<?php

namespace app\controllers;

use Yii;
use app\models\Cliente;
use app\models\ClienteModulo;
use app\components\DependentCRUDController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

class ClienteModuloController extends DependentCRUDController
{
    protected $dependentModel = 'Cliente';
    protected $parentField = 'cliente_id';
 
    private $_cliente;
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
            $this->_modulos = ClienteModulo::find()->doCliente($this->_cliente->id);
            
            return $this->renderAjaxOrLayout('create', [
                'model' => $model, 
                'cliente' => $this->_cliente, 
                'modulos' => $this->_modulos,
            ]);
        }
    }
    
    private function _init($model) {
        
        $this->_cliente = $this->parentObject;    
    }
}