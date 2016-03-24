<?php

namespace app\controllers;

use app\components\CRUDController;

class CasoDoencaController extends CRUDController
{
    public function actions()
    {
        return [
            'batch' => [
                'class' => 'app\\batch\\Action',
                'modelClass' => 'app\\models\\batch\\CasosDoenca',
            ]
        ];
    }
}
