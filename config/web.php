<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$logTargets = [
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning'],
    ]
];

if (YII_ENV_PROD) {
    $logTargets[] = [
        'class' => 'yii\log\EmailTarget',
        'mail' => 'mail',
        'levels' => ['error', 'warning'],
        'message' => [
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
            'authFile' => __DIR__ . '/../data/rbac.php',
            'defaultRoles' => ['Anonimo'],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtpi.vigilantus.com.br',
                'username' => 'vigilantus@vigilantus.com.br',
                'password' => 'f33dh1t5',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Usuario',
            'enableAutoLogin' => true,
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
