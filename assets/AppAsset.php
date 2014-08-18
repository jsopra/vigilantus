<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/resumo-rg-bairro.css',
        'css/index.css',
        'css/common.css',    
        'css/blog.css',
    ];
    public $js = [
        'js/feedback.js',
        'js/site.js',
        'js/grid.js',
        'js/typeahead.min.js',
        'js/jquery.numeric.min.js',
        'js/modernizr.vigilantus.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'app\components\themes\DetailwrapAsset',
        'app\batch\Asset',
    ];
}
