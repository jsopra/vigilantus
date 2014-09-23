<?php
use yii\console\controllers\MigrateController;

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'vigilantus-console',
    'name' => 'Vigilantus Console',
    'language' => 'pt-BR',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::className(),
            'migrationTable' => 'tbl_migration',
            'templateFile' => '@app/data/migrationsTemplate.php',
        ]
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => getenv('VIGILANTUS_REDIS_HOST'),
            'port' => getenv('VIGILANTUS_REDIS_DB_PORT'),
            'database' => 0,
            'password' => getenv('VIGILANTUS_REDIS_DB_PASSWORD'),
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'fixture' => [
            'class' => 'yii\test\DbFixtureManager',
            'basePath' => '@tests/unit/fixtures',
        ]
    ],
    'params' => $params,
];

if (file_exists(__DIR__ . '/test_db.php')) {
    
    $config['components']['testDb'] = [
        'class' => 'yii\db\Connection',
        'dsn' => getenv('VIGILANTUS_TEST_DB_DSN'),
        'username' => getenv('VIGILANTUS_TEST_DB_USERNAME'),
        'password' => getenv('VIGILANTUS_TEST_DB_PASSWORD'),
        'charset' => 'utf8',
    ];
}

return $config;
