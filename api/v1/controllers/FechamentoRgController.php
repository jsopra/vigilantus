<?php
namespace api\v1\controllers;

use api\rest\ActiveController;

class FechamentoRgController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\redis\ResumoBairroFechamentoRg';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $activeActions = parent::actions();
        return [
            'index' => [
                'class' => 'api\v1\controllers\actions\FechamentoRgIndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => $activeActions['options'],
        ];
    }
}
