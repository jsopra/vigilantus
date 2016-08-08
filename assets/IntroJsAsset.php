<?php
namespace app\assets;
use yii\web\AssetBundle;

class IntroJSAsset extends AssetBundle
{
    public $sourcePath = '@bower/intro.js';
    public $css = [
        'minified/introjs.min.css',
    ];

    public $js = [
        'minified/intro.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
