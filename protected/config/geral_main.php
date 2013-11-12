<?php

Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');

$commonConfig = require_once(dirname(__FILE__) . "/geral.php");

$aConfigDefault = array(
	'defaultController'=>'default',
    'modules' => array(
		'gii' => array(
			'class' => 'ext.internal.PGiiModule',
			'password' => 'lal',
			'ipFilters' => array('127.0.0.1', '::1'),
			'generatorPaths' => array(
				'ext.internal.gii',
				'bootstrap.gii',
			),
		),
	),
    'components' => array(
		'authManager' => array(
            'class' => 'UserRole',
        ),
		'user' => array(
			// enable cookie-based authentication
			'allowAutoLogin' => true,
			'loginUrl' => array('default/session/login'),
			'loginRequiredAjaxResponse' => 'YII_LOGIN_REQUIRED',
			'class' => 'WebUser',
			'authTimeout' => 60 * 30,
		),
		'errorHandler' => array(
			'errorAction' => '/default/session/error',
		),
		'bootstrap'=>array(
			'class'=>'bootstrap.components.Bootstrap', 
		),
        'session' => array(
			'savePath' =>  '/tmp/',
		),
    ),
);

return CMap::mergeArray($commonConfig,$aConfigDefault);