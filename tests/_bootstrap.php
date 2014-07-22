<?php

defined('TEST_ENTRY_URL') or define('TEST_ENTRY_URL', 'index.php');

defined('TEST_ENTRY_FILE') or define('TEST_ENTRY_FILE', dirname(__DIR__) . '/index.php');

defined('YII_DEBUG') or define('YII_DEBUG', true);

defined('YII_ENV') or define('YII_ENV', 'test');

require_once(__DIR__ . '/../vendor/autoload.php');

require_once(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

// set correct script paths
$_SERVER['SCRIPT_FILENAME'] = TEST_ENTRY_FILE;
$_SERVER['SCRIPT_NAME'] = TEST_ENTRY_URL;

Yii::setAlias('@tests', __DIR__);

// Dá acesso ao Yii::$app->db e outros
$application = new yii\console\Application(require 'unit/_config.php');

if (isset($_SERVER['argv']) && isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'build') {
    return;
}

use tests\TestHelper;
use tests\FactoryObjectBuilder;
use tests\FactoryObjectTrigger;

Phactory::builder(new FactoryObjectBuilder);
Phactory::triggers(new FactoryObjectTrigger);

foreach (scandir(__DIR__ . '/factories') as $file) {

    if (strlen($file) > 4 && substr($file, strlen($file) - 4) == '.php') {
        require_once __DIR__ . '/factories/' . $file;
    }
}

TestHelper::recreateDataBase();

require_once 'TesterDeAceitacao.php';
