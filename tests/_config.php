<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('VIGILANTUS_TEST_DB_DSN_HOST') . ';' . getenv('VIGILANTUS_TEST_DB_DSN_DBNAME'),
            'username' => getenv('VIGILANTUS_TEST_DB_USERNAME'),
            'password' => getenv('VIGILANTUS_TEST_DB_PASSWORD'),
            'charset' => 'utf8',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => getenv('VIGILANTUS_REDIS_HOST'),
            'port' => getenv('VIGILANTUS_REDIS_DB_PORT'),
            'database' => 1,
            'password' => getenv('VIGILANTUS_REDIS_DB_PASSWORD'),
        ],
        'mail' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
            'enablePrettyUrl' => false,
        ],
        'request' => [
            'cookieValidationKey' => 'secret_key',
        ],
    ],
];
