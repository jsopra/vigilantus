<?php

use app\models\ImovelTipo;
use app\models\Municipio;
use \Phactory;

if ($this->scenario->running()) {

    $usuario = Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

    $municipio = Municipio::find()->one();

    if (!$municipio) {
        $municipio = Phactory::municipio();
    }

    ImovelTipo::deleteAll();

    $casa = Phactory::imovelTipo(['nome' => 'Casa', 'municipio_id' => $municipio]);
    $terreno = Phactory::imovelTipo(['nome' => 'Terreno', 'municipio_id' => $municipio]);

    $baseDados = [
        'Seminário' => [
            '120' => [$casa->id => 0, $terreno->id => 4], // = 4
            '121' => [$casa->id => 10, $terreno->id => 0], // = 10
            '122' => [$casa->id => 4, $terreno->id => 1], // = 5
            // = 19 imoveis, 14 casa + 5 terreno
        ],
        'Palmital' => [
            '130' => [$casa->id => 2, $terreno->id => 2], // = 4
            '131' => [$casa->id => 3, $terreno->id => 4], // = 5
            // 11 imoveis, 5 casa + 6 terreno
        ],
        // 2 bairros, 5 quarteiroes, 30 imóveis = 19 casa + 11 terreno
    ];

    foreach ($baseDados as $nomeBairro => $quarteiroes) {

        $bairro = Phactory::bairro([
            'nome' => $nomeBairro,
            'municipio_id' => $municipio->id,
        ]);

        foreach ($quarteiroes as $numero => $imoveisPorTipo) {

            $quarteirao = Phactory::bairroQuarteirao([
                'numero_quarteirao' => $numero,
                'municipio_id' => $municipio->id,
                'bairro_id' => $bairro->id,
            ]);

            $boletim = Phactory::boletimRg([
                'data' => '07/03/1989',
                'municipio_id' => $municipio->id,
                'bairro_id' => $bairro->id,
                'bairro_quarteirao_id' => $quarteirao->id,
            ]);

            foreach ($imoveisPorTipo as $tipoImovel => $quantidade) {
                for ($n = 1; $n <= $quantidade; $n++) {
                    $boletim->adicionarImovel('Avenida', strval($n), null, null, $tipoImovel, false);
                }
            }

            if (false == $boletim->salvarComImoveis()) {
                throw new Exception('Erro ao preparar cenário!');
            }
        }
    }
}

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o relatório boletim resumo de reconhecimento geográfico funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->aguardoPor(1);

$eu->espero('ver os dados na home');
$eu->vejo(implode(' ', ['Quarteirões', '5']));
$eu->vejo(implode(' ', ['Casa', '19']));
$eu->vejo(implode(' ', ['Terreno', '11']));
$eu->vejo(implode(' ', ['Total', '30']));
$eu->vejo(implode(' ', ['Palmital', '11']));
$eu->vejo(implode(' ', ['Seminário', '19']));
