<?php

use tests\TestHelper;
use yii\web\Application;

new Application(require(__DIR__ . '/_config.php'));

TestHelper::recreateSchema();
