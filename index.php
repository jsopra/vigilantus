<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require('protected/config/defines.php');
$yii = CAMINHO_FRAMEWORK . 'yii.php';

$config = dirname(__FILE__) . '/protected/config/geral_main.php';

require_once($yii);

Yii::createWebApplication($config)->run();