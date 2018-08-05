<?php
namespace api\v1\controllers;

use api\rest\ActiveController;

class SemanaEpidemiologicaVisitaController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\SemanaEpidemiologicaVisita';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $activeActions = parent::actions();
        return [
            'index' => [
                'class' => 'api\v1\controllers\actions\SemanaEpidemiologicaVisitaIndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'update' => [
                'class' => 'api\v1\controllers\actions\SemanaEpidemiologicaVisitaUpdateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
            'view' => $activeActions['view'],
            'options' => $activeActions['options'],
        ];
    }
}
