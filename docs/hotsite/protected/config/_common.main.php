<?php
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'sourceLanguage'=>'pt_br',
    'language'=>'pt_br',
	'name' => 'Apoio e Gestão de prevenção da Dengue', 
	'preload'=>array(
		'log',
	),
    'import' => array(
		'application.components.*',
        'application.extensions.*',
		'application.models.forms.*',
    ),
    'components' => array(
		'urlManager'=>array(
            'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules' => array(
				'' => 'default/index',
			),
        ),
        'errorHandler' => array(
            'errorAction' => 'default/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
        'mail' => array(
            'class' => 'application.extensions.YiiMail',
            'transportType' => 'smtp',
            'transportOptions' => array(
                'host'      => 'smtp.mandrillapp.com',
                'username'  => 'tenha@perspectiva.in',
                'password'  => 'PHv0GKgYhJt5xv11iHKtgA',
                'port'      => '587',
            ),
            'viewPath' => 'application.views.email',
            'logging' => true,
            'dryRun' => false
        ),
		'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
    ),
    'params' => array(
        'adminEmail' => 'jsopra@gmail.com', 
		'fromMail' => 'reply@mailing.perspectiva.in',
    ),
);
