<?php
namespace app\modules\api\v1\controllers;

use app\modules\api\rest\ActiveController;

class AmostraTransmissorController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\AmostraTransmissor';
}
