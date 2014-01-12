<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => getenv('VIGILANTUS_DB_DSN'),
    'username' => getenv('VIGILANTUS_DB_USERNAME'),
    'password' => getenv('VIGILANTUS_DB_PASSWORD'),
    'charset' => 'utf8',
];
