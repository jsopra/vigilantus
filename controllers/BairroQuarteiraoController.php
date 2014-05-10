<?php

namespace app\controllers;

use Yii;
use app\models\Bairro;
use app\components\DependentCRUDController;

class BairroQuarteiraoController extends DependentCRUDController
{
    protected $dependentModel = 'Bairro';
    protected $parentField = 'bairro_id';
    
    public function actionIndex()
    {
        $municipio = $this->parentObject->municipio;
        if(!$municipio->loadCoordenadas()) {
            Yii::$app->session->setFlash('error', 'Município não tem coordenadas geográficas definidas');
            $this->redirect(['bairro/index']);
        }
        
        return parent::actionIndex();
    }
}