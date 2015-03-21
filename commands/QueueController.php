<?php
namespace app\commands;

use Yii;

class QueueController extends \perspectivain\gearman\WorkerController
{
    public function init()
    {
        Yii::$app->setTimeZone('America/Sao_Paulo');
    }

}
