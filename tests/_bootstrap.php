<?php

if (getenv('VIGILANTUS_ENV') != 'test') {
    throw new Exception('Parece que a variável de ambiente VIGILANTUS_ENV não é test. Verifique suas configurações!');
}

defined('TEST_ENTRY_URL') or define('TEST_ENTRY_URL', 'index.php');

// the entry script file path for functional and acceptance tests
defined('TEST_ENTRY_FILE') or define('TEST_ENTRY_FILE', dirname(__DIR__) . '/index.php');

defined('YII_DEBUG') or define('YII_DEBUG', true);

defined('YII_ENV') or define('YII_ENV', 'test');

require_once(__DIR__ . '/../vendor/autoload.php');

require_once(__DIR__ . '/../vendor/yiisoft/yii2/yii/Yii.php');

// set correct script paths
$_SERVER['SCRIPT_FILENAME'] = TEST_ENTRY_FILE;
$_SERVER['SCRIPT_NAME'] = TEST_ENTRY_URL;

Yii::setAlias('@tests', __DIR__);
