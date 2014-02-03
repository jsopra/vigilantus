<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components\themes;

use yii\web\AssetBundle;

class DetailwrapAsset extends AssetBundle
{
    public $sourcePath = '@vendor/../components/themes/detailwrap';
    public $css = [
        'css/bootstrap/bootstrap.css',
        'css/bootstrap/bootstrap-overrides.css',
        'css/lib/jquery-ui-1.10.2.custom.css',
        'css/lib/font-awesome.css',
        'css/compiled/layout.css',
        'css/compiled/elements.css',
        'css/compiled/icons.css',
        'css/lib/uniform.default.css', 
        'css/compiled/form-wizard.css',
    ];
    public $js = [
        'js/bootstrap.min.js',
        'js/jquery-ui-1.10.2.custom.min.js',
        'js/theme.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset'
    ];
}