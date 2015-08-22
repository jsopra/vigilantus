<?php
namespace api\v1\controllers;

use api\rest\ActiveController;

class AmostraTransmissorController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\AmostraTransmissor';
}
