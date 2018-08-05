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
        return [
            'index' => [
                'class' => 'api\v1\controllers\actions\EquipeIndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $activeActions = parent::actions();
        return [
            'index' => $activeActions['index'],
            'options' => $activeActions['options'],
        ];
    }

    public function actionIndex()
    {

    }
}
