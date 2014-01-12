<?php
require('protected/config/defines.php');
$yii = CAMINHO_FRAMEWORK . 'yii.php';

$config = dirname(__FILE__) . '/protected/config/main.php';

require_once($yii);

Yii::createWebApplication($config)->run();