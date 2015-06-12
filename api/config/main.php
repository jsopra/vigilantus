<?php

$baseDir = dirname(__DIR__);

return [
    'id' => 'hadnu-backend',
    'name' => 'Hadnu API',
    'version' => '1.0',
    'sourceLanguage' => 'en',
    'language' => 'pt-BR',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => 'api\v1\Module',
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('VIGILANTUS_DB_DSN_HOST') . ';' . getenv('VIGILANTUS_DB_DSN_DBNAME'),
            'username' => getenv('VIGILANTUS_DB_USERNAME'),
            'password' => getenv('VIGILANTUS_DB_PASSWORD'),
            'charset' => 'utf8',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/session'],
                ]
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
        ],
    ],
];
