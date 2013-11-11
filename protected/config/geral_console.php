<?php

Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');

$commonConfig = require_once(dirname(__FILE__) . "/geral.php");
unset($commonConfig['components']['session']);

$aConfigDefault =  array(
	'name'=>'Vigilantus Console',
	'components'=>array(
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'logPath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'log',
					'logFile'=>'command.log',
				),
			),
		),
	),
);

return CMap::mergeArray($commonConfig,$aConfigDefault);