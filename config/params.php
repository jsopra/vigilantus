<?php
return [
    //geral
    'adminEmail' => 'admin@example.com',
    'emailContato' => 'tenha@perspectiva.in',
    'emailFeedback' => [
        'julianobaggiodisopra+eytiwy7qw9cpdnten2an@boards.trello.com',
        'tenha@perspectiva.in',
    ],
    'dataDir' => getenv('OPENSHIFT_DATA_DIR'),
    'publicDir' => getenv('OPENSHIFT_HOMEDIR') . '/' . getenv('VIGILANTUS_PUBLIC_FOLDER'),
    //geolocalização
    'googleMapsAPIKey' => 'AIzaSyDxnC_bLYD97iaRgZ2qGts6_7IE8KOYLik',
    'postgisSRID' => 4326, /* Spatial Reference System Identifier (SRID). Usamos o WGS84 */
    'maximoTermosMonitoramentoRedesSociaisPorCliente' => 3,
    'gearmanQueueName' => 'vigilantus', /* também referenciado estaticamente por .openshift/cron/minutely/queue.sh */
    'twitter' => [
        'app_key' => 'RvwgUXj0vvpkd68WHHiBBhPZv',
        'app_secret' => 'g6UhInqNfTtIpeXjdIwrilLvJYa3k4GDhMgc8YjrausUAU4JO5',
    ],
    'facebook' => [
        'app_key' => '451847198312682',
        'app_secret' => '92cb51d562f5af814469576f115b24b3',
        //'app_key' => '451847534979315',
        //'app_secret' => 'c63aed81ff578633a1483e8835e93adc',
    ],
    'instagram' => [
        'app_key' => '2e27d4f38b624f45b0f3323070cb19b2',
        'app_secret' => '8f4f65a441ec463e911b4f3115ee62cc',
        //'app_key' => '86b191c2e1214f57bdde569a2514f3b0',
        //'app_secret' => '6e55d6d21d764cdcbd9254f1db00e7ff',
    ],
];
