<?php
use yii\helpers\StringHelper;

$controllerClass = StringHelper::basename($generator->controllerClass);

echo "<?php\n"; 
?>

namespace app\controllers;

use app\components\CRUDController;

class <?= $controllerClass; ?> extends CRUDController
{
    
}