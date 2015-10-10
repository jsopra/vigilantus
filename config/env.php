<?php
$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->overload();

try {
    $dotenv->required([
        'COOKIES_KEY', 'ABSOLUTE_URL',
        'DB_DSN', 'DB_USERNAME',
        'REDIS_HOST', 'REDIS_DATABASE', 'REDIS_PORT',
        'GEARMAN_IP', 'GEARMAN_PORT', 'GEARMAN_JOB_KEY',
        'SMTP_HOST', 'SMTP_USERNAME', 'SMTP_PORT', 'SMTP_ENCRYPTION',
        'TWITTER_KEY', 'TWITTER_SECRET',
        'FACEBOOK_KEY', 'FACEBOOK_SECRET',
        'INSTAGRAM_KEY', 'INSTAGRAM_SECRET',
        'GOOGLE_MAPS_KEY', 'MAP_BOX_ACCESS_TOKEN', 'MAP_BOX_MAP_ID',
    ])->notEmpty();

    // É obrigatória, mas pode estar vazia: ""
    $dotenv->required([
        'REDIS_PASSWORD',
        'DB_PASSWORD',
        'SMTP_PASSWORD',
    ]);

    $dotenv->required('ENVIRONMENT')->allowedValues(['development', 'test', 'production']);

} catch (Exception $e) {
    echo "Verifique o arquivo \".env\":\n";
    echo $e->getMessage(), "\n";
    exit(1);
}
