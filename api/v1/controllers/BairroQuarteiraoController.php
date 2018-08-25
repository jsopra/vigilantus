<?php
namespace api\v1\controllers;

use api\rest\ActiveController;

class BairroQuarteiraoController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\BairroQuarteirao';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $activeActions = parent::actions();
        return [
            'index' => [
                'class' => 'api\v1\controllers\actions\BairroQuarteiraoIndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => $activeActions['view'],
            'options' => $activeActions['options'],
        ];
    }
}
