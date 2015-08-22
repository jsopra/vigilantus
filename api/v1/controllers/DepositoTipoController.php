<?php
namespace api\v1\controllers;

use api\rest\ActiveController;

class DepositoTipoController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\DepositoTipo';

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
