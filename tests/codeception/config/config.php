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
            'hostname' => getenv('VIGILANTUS_REDIS_HOST'),
            'port' => getenv('VIGILANTUS_REDIS_DB_PORT'),
            'database' => getenv('VIGILANTUS_REDIS_DB_NUMBER') ?: 1,
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'phactory' => 'fidelize\phactory\Component',
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];

if ($redisPassword = getenv('VIGILANTUS_REDIS_DB_PASSWORD')) {
    $config['components']['redis']['password'] = $redisPassword;
} elseif (array_key_exists('password', $config['components']['redis'])) {
    unset($config['components']['redis']['password']);
}

return $config;
