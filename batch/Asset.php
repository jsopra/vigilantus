<?php

namespace app\batch;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $js = [
        'js/batch.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
