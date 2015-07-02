<?php
namespace app\modules\api\v1\controllers;

use app\modules\api\rest\ActiveController;

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
            'index' => $activeActions['index'],
            'view' => $activeActions['view'],
            'options' => $activeActions['options'],
        ];
    }
}
