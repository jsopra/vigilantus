<?php
$commonConfig = require_once(dirname(__FILE__) . "/geral.php");
unset($commonConfig['components']['session']);
unset($commonConfig['components']['user']);

$aConfigDefault =  array(
	'name'=>'Vigilantus Test',
    'preload'=>array('log'),
	'import' => array(
		'application.tests.*',
	),
	'components'=>array(
		'fixture'=>array(
                'class'=>'application.tests.PDbFixtureManager',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning, trace, info',
                    'categories'=>'system.*',
                    'logPath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'log',
                    'logFile'=>'test.log',
                ),
 
            ),
        ),
        
	),
);

$geralTest = CMap::mergeArray($commonConfig,$aConfigDefault);

$geralTest['components']['db'] = $geralTest['components']['testDb'];

return $geralTest;