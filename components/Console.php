<?php
namespace app\components;

use Yii;

class Console extends yii\console\Controller
{
	public function init()
    {
        Yii::$app->setTimeZone('America/Sao_Paulo');
    }
    
}