<?php

// carrega lista de municípios a partir de um arquivo YAML simples
$municipios = [];
$arquivoMunicipios = __DIR__ . '/municipios.yaml';
$chaveAtual = null;
$atributoAtual = null;

if (file_exists($arquivoMunicipios)) {
    foreach (file($arquivoMunicipios) as $linha) {
        $bruta = rtrim($linha);
        if ($bruta === '' || strpos($bruta, '#') === 0) {
            continue;
        }

        // linhas sem indentação representam um novo município
        if ($bruta === ltrim($bruta) && preg_match('/^([\w\-]+):$/', $bruta, $matches)) {
            $chaveAtual = $matches[1];
            $municipios[$chaveAtual] = [];
            $atributoAtual = null;
            continue;
        }

        $linha = trim($bruta);

        if ($chaveAtual !== null && preg_match('/^([\w]+):\s*(.*)$/', $linha, $matches)) {
            $atributoAtual = $matches[1];
            $valor = trim($matches[2], "'\"");
            if ($valor === '') {
                $municipios[$chaveAtual][$atributoAtual] = [];
                continue;
            }
            if (is_numeric($valor)) {
                $valor += 0;
            }
            $municipios[$chaveAtual][$atributoAtual] = $valor;
            continue;
        }

        if ($chaveAtual !== null && $atributoAtual !== null && preg_match('/^-\s*(.+)$/', $linha, $matches)) {
            $valor = trim($matches[1], "'\"");
            if (is_numeric($valor)) {
                $valor += 0;
            }
            if (!isset($municipios[$chaveAtual][$atributoAtual]) || !is_array($municipios[$chaveAtual][$atributoAtual])) {
                $municipios[$chaveAtual][$atributoAtual] = [];
            }
            $municipios[$chaveAtual][$atributoAtual][] = $valor;
        }
    }
}

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
    'municipios' => $municipios,
];
