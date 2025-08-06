<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$logTargets = [
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning'],
    ]
];

if (getenv('ENVIRONMENT') == 'production') {
    $logTargets[] = [
        'class' => 'yii\log\EmailTarget',
        'mailer' => 'mail',
        'levels' => ['error', 'warning'],
            'message' => [
                'from' => [$params['emailContato']],
                'to' => ['dev@vigilantus.com.br'],
                'subject' => 'Application Log',
            ],
    ];
}

$config = [
    'id' => 'vigilantus',
    'name' => 'Vigilantus',
    'language' => 'pt-BR',
    'basePath' => dirname(__DIR__),
    'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
    'components' => [
        'authManager' => [
            'class' => 'app\components\AuthManager',
            //'authFile' => __DIR__ . '/../data/rbac.php',
            'defaultRoles' => ['Anonimo'],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'consumerKey' => getenv('TWITTER_KEY'),
                    'consumerSecret' => getenv('TWITTER_SECRET'),
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => getenv('FACEBOOK_KEY'),
                    'clientSecret' => getenv('FACEBOOK_SECRET'),
                    'scope' => 'email, publish_actions, user_friends'
                ],
                'instagram' => [
                    'class' => 'app\components\clients\Instagram',
                    'clientId' => getenv('INSTAGRAM_KEY'),
                    'clientSecret' => getenv('INSTAGRAM_SECRET'),
                    'scope' => 'likes relationships',
                ],
            ]
        ],
        'gearman' => [
            'class' => 'perspectivain\gearman\Gearman',
            'jobsNamespace' => '\app\jobs\\',
            'servers' => [
                ['host' => getenv('GEARMAN_IP'), 'port' => getenv('GEARMAN_PORT')],
            ],
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => getenv('REDIS_HOST'),
            'port' => getenv('REDIS_PORT'),
            'database' => getenv('REDIS_DATABASE'),
            'password' => getenv('REDIS_PASSWORD'),
        ],
        'session' => [
            'class' => 'yii\redis\Session',
        ],
        'db' => $db,
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'medium',
            'datetimeFormat' => 'medium',
            'timeFormat' => 'medium',
            'timeZone' => 'America/Sao_Paulo',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => $logTargets,
        ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('SMTP_HOST'),
                'username' => getenv('SMTP_USERNAME'),
                'password' => getenv('SMTP_PASSWORD'),
                'port' => getenv('SMTP_PORT'),
                'encryption' => getenv('SMTP_ENCRYPTION'),
            ],
        ],
        'request' => [
            'cookieValidationKey' => getenv('COOKIES_KEY'),
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Usuario',
            'enableAutoLogin' => true,
        ],
        'response' => [
            'formatters' => [
                'pdf' => [
                    'class' => 'robregonm\pdf\PdfResponseFormatter',
                ],
            ]
        ],
        's3' => [
            'class' => \frostealth\yii2\aws\s3\Storage::className(),
            'credentials' => [ // Aws\Credentials\CredentialsInterface|array|callable
                'key' => getenv('S3_KEY'),
                'secret' => getenv('S3_SECRET'),
            ],
            'region' => getenv('S3_REGION'),
            'bucket' => getenv('S3_BUCKET'),
            'defaultAcl' => getenv('S3_ACL'),
            //'cdnHostname' => 'http://example.cloudfront.net',
            'debug' => false, // bool|array
        ],
    ],
    'bootstrap' => [
        'app\ocorrencia\Bootstrap',
    ],
    'modules' => [
        'ocorrencia' => ['class' => 'app\ocorrencia\Module'],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['172.17.0.1'],
        'generators' => [
            'vigilantus-model' => ['class' => 'app\extensions\gii\generators\model\Generator'],
            'vigilantus-crud' => ['class' => 'app\extensions\gii\generators\crud\Generator'],
            'vigilantus-controller' => ['class' => 'app\extensions\gii\generators\controller\Generator'],
        ],
    ];
}

if (empty($config['components']['redis']['password'])) {
    unset($config['components']['redis']['password']);
}

return $config;
