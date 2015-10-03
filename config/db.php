<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => getenv('DB_DSN_HOST') . ';' . getenv('DB_DSN_DBNAME'),
    'username' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => 'utf8',
];
