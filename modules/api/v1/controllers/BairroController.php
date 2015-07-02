<?php
namespace app\modules\api\v1\controllers;

use app\modules\api\rest\ActiveController;

class BairroController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\Bairro';

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
