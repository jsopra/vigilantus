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
		
		'errorHandler' => array(
			'errorAction' => '/default/default/error',
		),
		'bootstrap'=>array(
			'class'=>'bootstrap.components.Bootstrap', 
		),
		'user' => array(
			'allowAutoLogin' => true,
			'loginUrl' => array('default/session/login'),
			/*
			'loginRequiredAjaxResponse' => 'YII_LOGIN_REQUIRED',
			'class' => 'WebUser',
			*/
		),
		/*
		'authManager' => array(
            'class' => 'UserRole',
        ),
		 */
    ),
);

return CMap::mergeArray($commonConfig,$aConfigDefault);