<?php
use yii\console\controllers\MigrateController;

/**
 * Application configuration for unit tests
 */
$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../../config/web.php'),
    require(__DIR__ . '/config.php'),
    [
        'controllerMap' => [
            'migrate' => [
                'class' => MigrateController::className(),
                'migrationTable' => 'tbl_migration',
                'templateFile' => '@app/data/migrationsTemplate.php',
            ]
        ],
    ]
);

// Remove action do errorHandler que só funciona na web
unset($config['components']['errorHandler']);

return $config;
