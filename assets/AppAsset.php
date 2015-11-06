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
        'css/cidade.css',
        'css/common.css',
        'css/blog.css',
        'css/ocorrencias.css',
        'css/timeline.css',
        'js/jquery-toast-plugin-master/jquery.toast.min.css',
    ];
    public $js = [
        'js/feedback.js',
        'js/site.js',
        'js/grid.js',
        'js/typeahead.min.js',
        'js/jquery.numeric.min.js',
        'js/modernizr.vigilantus.min.js',
        'js/social-login.js',
        'js/stepguide.js',
        'js/jquery.geolocation.js',
        'js/jquery-toast-plugin-master/jquery.toast.min.js',
        'js/jquery.balancetext.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'app\components\themes\DetailwrapAsset',
        'app\batch\Asset',
        'app\assets\IntroJsAsset',
        '\rmrevin\yii\fontawesome\AssetBundle'
    ];
}
