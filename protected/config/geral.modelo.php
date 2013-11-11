<?php
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'sourceLanguage'=>'pt_br',
    'language'=>'pt_br',
	'name' => 'Vigilantus', 
	'preload'=>array(
		'log',
	),
    'import' => array(
        'application.components.*',
        'application.extensions.*',
		'application.models.*',
		'application.models.forms.*',
		'application.jobs.*',
    ),
	'modules'=>array(
        'default' => array(
			'import' => array(
				'application.modules.default.models.forms.*',
			),
		),
    ),
    'components' => array(
		'gearman' => array(
				'class' => 'commonLibrary.extensions.Gearman',
				'servers' => array(
						array('host' => '0.0.0.0', 'port' => 4730),
				),
		),
		'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
					'logPath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'log',
                ),
            ),
        ),
        'mail' => array(
            'class' => 'commonLibrary.extensions.yii-mail.YiiMail',
            /*'transportType' => 'smtp',
            'transportOptions' => array(
                'host'      => 'smtp.fusadhfusahuf.com',
                'username'  => 'fasfsafsa@asfasfasf',
                'password'  => 'asffasfasfasf',
                //'port'      => '465',
                //'encryption'=> 'ssl',
            ),*/
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => false
        ),
		'db' => array(
			'class' => 'CDbConnection',
			'connectionString' => 'pgsql:host=pgsql.perspectiva.in;port=5432;dbname=dengue',
			'username' => 'perspectiva2',
			'password' => 'd8nA5Nf#12Ss',
			'charset' => 'utf8',
		),
        'testDb' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'pgsql:host=localhost;port=5432;dbname=dengue_test',
            'username' => 'postgres',
            'enableProfiling'=>true,
            'password' => 'victor',
            'charset' => 'utf8',
        ),
    ),
    'params' => array(
        'adminEmail' => 'dengue@perspectiva.in',  
    ),
);