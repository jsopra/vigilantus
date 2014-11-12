<?php
$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../config/web.php'),
    require(__DIR__ . '/../_config.php')
);

// Remove action do errorHandler que só funciona na web
unset($config['components']['errorHandler']);

return $config;