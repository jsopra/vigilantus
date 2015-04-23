<?php

namespace app\controllers;

use Yii;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\components\DependentCRUDController;
use app\helpers\GoogleMapsAPIHelper;

class BairroQuarteiraoController extends DependentCRUDController
{
    protected $dependentModel = 'Bairro';
    protected $parentField = 'bairro_id';

    private $_municipio;
    private $_bairro;
    private $_quarteiroes;

    public function actionIndex()
    {
        $municipio = $this->parentObject->municipio;
        if(!$municipio->loadCoordenadas()) {
            Yii::$app->session->setFlash('error', 'Município não tem coordenadas geográficas definidas');
            $this->redirect(['bairro/index']);
        }

        return parent::actionIndex();
    }

    public function actionCreate()
    {
        $model = $this->buildNewModel();

        $parentField = $this->parentField;
        $model->$parentField = $this->parentObject->id;

        if (!$this->loadAndSaveModel($model, $_POST, ['index', 'parentID' => $this->dependentID])) {
            $this->_init($model);
            $this->_quarteiroes = BairroQuarteirao::find()->doBairro($this->_bairro->id)->comCoordenadas();

            return $this->renderAjaxOrLayout('create', [
                'model' => $model,
                'municipio' => $this->_municipio,
                'bairro' => $this->_bairro,
                'quarteiroes' => $this->_quarteiroes,
                'coordenadasQuarteiroes' => BairroQuarteirao::getCoordenadas($this->_quarteiroes)
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
            $this->_quarteiroes = BairroQuarteirao::find()->queNao($model->id)->doBairro($this->_bairro->id)->comCoordenadas();
            $model->loadCoordenadas();
            if(!$model->getIsNewRecord() && !$model->coordenadasJson && $model->coordenadas) {
                $model->coordenadasJson = GoogleMapsAPIHelper::arrayToCoordinatesJson($model->coordenadas);
            }

            return $this->renderAjaxOrLayout('update', [
                'model' => $model,
                'municipio' => $this->_municipio,
                'bairro' => $this->_bairro,
                'quarteiroes' => $this->_quarteiroes,
                'coordenadasQuarteiroes' => BairroQuarteirao::getCoordenadas($this->_quarteiroes)
            ]);
        }
    }

    private function _init($model) {

        $this->_bairro = $this->parentObject;
        $this->_bairro->loadCoordenadas();

        $this->_municipio = $this->_bairro->municipio;
        $this->_municipio->loadCoordenadas();
    }
}
