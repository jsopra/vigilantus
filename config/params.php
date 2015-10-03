<?php
return [
    //geral
    'adminEmail' => 'admin@example.com',
    'emailContato' => 'tenha@perspectiva.in',
    'emailFeedback' => [
        'julianobaggiodisopra+lxkw03zrqfb8dowhdjye@boards.trello.com',
        'tenha@perspectiva.in',
    ],
    'dataDir' => __DIR__ . '/../data/',
    'publicDir' => __DIR__ . '/../web/',
    //geolocalização
    'googleMapsAPIKey' => getenv('GOOGLE_MAPS_KEY'),
    'postgisSRID' => 4326, /* Spatial Reference System Identifier (SRID). Usamos o WGS84 */
    'maximoTermosMonitoramentoRedesSociaisPorCliente' => 3,
    'gearmanQueueName' => 'vigilantus', /* também referenciado estaticamente por .openshift/cron/minutely/queue.sh */
];
