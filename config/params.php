<?php

$clientConfig = [];

$clientJson = __DIR__ . '/client.json';
$clientYaml = __DIR__ . '/client.yaml';
$clientExample = __DIR__ . '/client.example.json';

if (file_exists($clientJson)) {
    $clientConfig = json_decode(file_get_contents($clientJson), true) ?: [];
} elseif (file_exists($clientYaml) && function_exists('yaml_parse_file')) {
    $clientConfig = yaml_parse_file($clientYaml) ?: [];
} elseif (file_exists($clientExample)) {
    $clientConfig = json_decode(file_get_contents($clientExample), true) ?: [];
}

return array_merge([
    //geolocalização
    'googleMapsAPIKey' => getenv('GOOGLE_MAPS_KEY'),
    'mapBoxAccessToken' => getenv('MAP_BOX_ACCESS_TOKEN'),
    'mapBoxMapID' => getenv('MAP_BOX_MAP_ID'),
    'postgisSRID' => 4326, /* Spatial Reference System Identifier (SRID). Usamos o WGS84 */
    'maximoTermosMonitoramentoRedesSociaisPorCliente' => 3,
    'gearmanQueueName' => 'vigilantus', /* também referenciado estaticamente por .openshift/cron/minutely/queue.sh */
], $clientConfig);
