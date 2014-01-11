<?php
return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../config/web.php'),
    require(__DIR__ . '/../_config.php'),
    [
        'components' => [
            'fixture' => [
                'class' => 'yii\test\DbFixtureManager',
                'basePath' => '@tests/unit/fixtures',
            ],
            'db' => [
                'dsn' => getenv('VIGILANTUS_DB_DSN'),
                'username' => getenv('VIGILANTUS_DB_USERNAME'),
                'password' => getenv('VIGILANTUS_DB_PASSWORD'),
            ],
            'phactory' => [
                'class' => 'hadnu\phactory\Base',
                'loader' => [
                    'class' => 'hadnu\phactory\Loader',
                    'factoriesNamespace' => 'tests\factories',
                ]
            ],
        ],
    ]
);
