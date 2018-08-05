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
            'index' => [
                'class' => 'api\v1\controllers\actions\EquipeAgenteIndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => $activeActions['options'],
        ];
    }
}
