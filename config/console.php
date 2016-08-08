<?php
use yii\console\controllers\MigrateController;

Yii::setAlias('@webroot', __DIR__ . '/../web');
Yii::setAlias('@web', '/');
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
        ],
    ],
    'bootstrap' => [
        'app\ocorrencia\Bootstrap',
    ],
    'modules' => [
        'ocorrencia' => ['class' => 'app\ocorrencia\Module'],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'gearman' => [
            'class' => 'perspectivain\gearman\Gearman',
            'jobsNamespace' => '\app\jobs\\',
            'servers' => [
                ['host' => getenv('GEARMAN_IP'), 'port' => getenv('GEARMAN_PORT')],
            ],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => getenv('REDIS_HOST'),
            'port' => getenv('REDIS_PORT'),
            'database' => getenv('REDIS_DATABASE'),
            'password' => getenv('REDIS_PASSWORD'),
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
        ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('SMTP_HOST'),
                'username' => getenv('SMTP_USERNAME'),
                'password' => getenv('SMTP_PASSWORD'),
                'port' => getenv('SMTP_PORT'),
                'encryption' => getenv('SMTP_ENCRYPTION'),
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'medium',
            'datetimeFormat' => 'medium',
            'timeFormat' => 'medium',
            'timeZone' => 'America/Sao_Paulo',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'hostInfo' => getenv('VIGILANTUS_BASE_PATH'),
            'scriptUrl' => getenv('VIGILANTUS_BASE_PATH') . '/index.php',
            'baseUrl' => '',
        ],
    ],
    'params' => $params,
];

if (empty($config['components']['redis']['password'])) {
    unset($config['components']['redis']['password']);
}

return $config;
