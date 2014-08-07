<?php

namespace app\controllers;

use app\components\CRUDController;
use app\batch\controller\Batchable;

class EspecieTransmissorController extends CRUDController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['only'][] = 'batch';
        $behaviors['access']['rules'][0]['actions'][] = 'batch';

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'batch' => [
                'class' => 'app\\batch\\Action',
                'modelClass' => 'app\\models\\batch\\EspecieTransmissor',
            ]
        ];
    }
}
