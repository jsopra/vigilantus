<?php
namespace api\v1\controllers;

use api\rest\ActiveController;

class EquipeAgenteController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\EquipeAgente';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $activeActions = parent::actions();
        return [
            'index' => $activeActions['index'],
            'view' => $activeActions['view'],
            'options' => $activeActions['options'],
        ];
    }
}
