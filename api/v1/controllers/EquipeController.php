<?php
namespace api\v1\controllers;

use api\rest\ActiveController;

class EquipeController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\Equipe';

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
