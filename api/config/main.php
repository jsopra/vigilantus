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
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
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
                    'controller' => [
                        'v1/session',
                        'v1/bairro',
                        'v1/quarteiroes' => 'v1/bairro-quarteirao',
                        'v1/tipos-depositos' => 'v1/deposito-tipo',
                        'v1/tipos-imoveis' => 'v1/imovel-tipo',
                        'v1/visita-atividades' => 'v1/visita-atividade',
                        'v1/equipes' => 'v1/equipe',
                        'v1/agentes' => 'v1/equipe-agente',
                        'v1/semanas-epidemiologicas' => 'v1/semana-epidemiologica',
                        'v1/visitas-semanas-epidemiologicas' => 'v1/semana-epidemiologica-visita',
                    ],
                ]
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\Usuario',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
        ],
    ],
];
