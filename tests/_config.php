<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('VIGILANTUS_TEST_DB_DSN'),
            'username' => getenv('VIGILANTUS_TEST_DB_USERNAME'),
            'password' => getenv('VIGILANTUS_TEST_DB_PASSWORD'),
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'showScriptName' => true,
            'enablePrettyUrl' => false,
        ],
    ],
];
