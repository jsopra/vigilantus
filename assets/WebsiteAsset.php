<?php

namespace app\assets;

use yii\web\AssetBundle;

class WebsiteAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/ocorrencias.css',
        'css/timeline.css',
        'css/website.css',
        'css/ocorrencia-cidade.css',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        '\rmrevin\yii\fontawesome\AssetBundle'
    ];
}
