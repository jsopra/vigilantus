<?php
$currentDir = dirname(__DIR__);

$_SERVER['SERVER_NAME'] = 'vigilantus.test.dev';
$_SERVER['SERVER_ADDR'] = '::1';
$_SERVER['SERVER_PORT'] = '80';
$_SERVER['DOCUMENT_ROOT'] = $currentDir . '/../../web';
$_SERVER['REQUEST_SCHEME'] = 'http';
$_SERVER['CONTEXT_DOCUMENT_ROOT'] = $currentDir . '/../../web';
$_SERVER['SCRIPT_FILENAME'] = $currentDir . '/../../web/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

new yii\web\Application(require($currentDir . '/config/acceptance.php'));
