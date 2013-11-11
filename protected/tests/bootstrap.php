<?php

require('../../protected/config/defines.php');

$yiit = CAMINHO_FRAMEWORK . 'yiit.php';
$config = dirname(__FILE__).'/../config/geral_test.php';

require_once($yiit);
 
// automatically send every new message to available log routes
Yii::getLogger()->autoFlush = 1;
// when sending a message to log routes, also notify them to dump the message
// into the corresponding persistent storage (e.g. DB, email)
Yii::getLogger()->autoDump = true;

Yii::createWebApplication($config);
