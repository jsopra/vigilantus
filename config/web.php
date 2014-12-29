<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$logTargets = [
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning'],
    ]
];

$cookieValidationKey = getenv('VIGILANTUS_DB_DSN_HOST') . ';' . getenv('VIGILANTUS_DB_DSN_DBNAME');

if (getenv('VIGILANTUS_COOKIES_KEY')) {
    $cookieValidationKey = getenv('VIGILANTUS_COOKIES_KEY');
}

if (getenv('VIGILANTUS_ENV') == 'prod') {
    $logTargets[] = [
        'class' => 'yii\log\EmailTarget',
        'mailer' => 'mail',
        'levels' => ['error', 'warning'],
        'message' => [
            'from' => ['tenha@perspectiva.in'],
            'to' => ['dev@vigilantus.com.br'],
            'subject' => 'Application Log',
        ],
    ];
}

$config = [
    'id' => 'vigilantus',
    'name' => 'Vigilantus',
    'language' => 'pt-BR',
    'basePath' => dirname(__DIR__),
    'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
    'components' => [
        'authManager' => [
            'class' => 'app\components\AuthManager',
            //'authFile' => __DIR__ . '/../data/rbac.php',
            'defaultRoles' => ['Anonimo'],
        ],
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
        'db' => $db,
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'medium',
            'datetimeFormat' => 'medium',
            'timeFormat' => 'medium',
            'timeZone' => 'America/Sao_Paulo',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => $logTargets,
        ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mandrillapp.com',
                'username' => 'jsopra@gmail.com',
                'password' => 'KzL9E8rMpAd6Ux0pv7Lmbg',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'request' => [
            'cookieValidationKey' => $cookieValidationKey,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [

            ],
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Usuario',
            'enableAutoLogin' => true,
        ],
    ],
    'modules' => [
        'denuncia' => [
            'class' => 'app\modules\denuncia\Module',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'vigilantus-model' => ['class' => 'app\extensions\gii\generators\model\Generator'],
            'vigilantus-crud' => ['class' => 'app\extensions\gii\generators\crud\Generator'],
            'vigilantus-controller' => ['class' => 'app\extensions\gii\generators\controller\Generator'],
        ],
    ];
}

return $config;
