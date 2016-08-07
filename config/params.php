<?php
return [
    //geral
    'adminEmail' => 'admin@example.com',
    'emailContato' => 'tenha@perspectiva.in',
    'emailFeedback' => [
        'julianobaggiodisopra+lxkw03zrqfb8dowhdjye@boards.trello.com',
        'tenha@perspectiva.in',
    ],
    //geolocalização
    'googleMapsAPIKey' => getenv('GOOGLE_MAPS_KEY'),
    'mapBoxAccessToken' => getenv('MAP_BOX_ACCESS_TOKEN'),
    'mapBoxMapID' => getenv('MAP_BOX_MAP_ID'),
    'postgisSRID' => 4326, /* Spatial Reference System Identifier (SRID). Usamos o WGS84 */
    'maximoTermosMonitoramentoRedesSociaisPorCliente' => 3,
    'gearmanQueueName' => 'vigilantus', /* também referenciado estaticamente por .openshift/cron/minutely/queue.sh */
];
