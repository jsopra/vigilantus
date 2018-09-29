<?php
namespace api\v1\controllers;

use api\rest\ActiveController;

class SemanaEpidemiologicaController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\SemanaEpidemiologica';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $activeActions = parent::actions();
        return [
            'index' => [
                'class' => 'api\v1\controllers\actions\SemanaEpidemiologicaIndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => $activeActions['view'],
            'options' => $activeActions['options'],
        ];
    }
}
