<?php

$config = [
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
            'hostname' => getenv('REDIS_HOST'),
            'port' => getenv('REDIS_PORT'),
            'database' => getenv('REDIS_DATABASE') ?: 1,
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'phactory' => 'perspectiva\phactory\Component',
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];

if ($redisPassword = getenv('REDIS_PASSWORD')) {
    $config['components']['redis']['password'] = $redisPassword;
} elseif (array_key_exists('password', $config['components']['redis'])) {
    unset($config['components']['redis']['password']);
}

return $config;
