<?php
return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../config/web.php'),
    require(__DIR__ . '/../_config.php'),
    [
        'components' => [
            'fixture' => [
                'class' => 'tests\DbFixtureManager',
                'basePath' => '@tests/unit/fixtures',
            ],
        ],
    ]
);
