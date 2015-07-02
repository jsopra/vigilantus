<?php
namespace app\modules\api\v1\controllers;

use app\modules\api\rest\ActiveController;

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
