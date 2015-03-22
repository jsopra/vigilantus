<?php

use app\models\ImovelTipo;
use app\models\Cliente;
use app\models\BoletimRgFechamento;
use app\models\redis\FechamentoRg as FechamentoRgRedis;

$eu = new TesterDeAceitacao($scenario);

$cliente = Cliente::find()->andWhere('id=1')->one();

$usuario = Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

ImovelTipo::deleteAll();

$casa = Phactory::imovelTipo(['nome' => 'Casa', 'cliente_id' => $cliente]);
$terreno = Phactory::imovelTipo(['nome' => 'Terreno', 'cliente_id' => $cliente]);

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
        'cliente_id' => $cliente->id,
    ]);

    foreach ($quarteiroes as $numero => $imoveisPorTipo) {

        $quarteirao = Phactory::bairroQuarteirao([
            'numero_quarteirao' => strval($numero),
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
        ]);

        $boletim = Phactory::boletimRg([
            'data' => '07/03/1989',
            'cliente_id' => $cliente->id,
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

FechamentoRgRedis::deleteAll();

$query = BoletimRgFechamento::find()->doCliente($cliente->id)->doTipoLira(false);

$query->innerJoin('boletins_rg', 'boletim_rg_fechamento.boletim_rg_id=boletins_rg.id');
$query->andWhere('
    boletins_rg.data = (
        SELECT MAX(data)
        FROM boletins_rg brg
        WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
    )
');

$fechamentos = $query->all();
foreach($fechamentos as $boletimFechamento) {
    $fechamento = new FechamentoRgRedis;
    $fechamento->cliente_id = $cliente->id;
    $fechamento->bairro_quarteirao_id =  $boletimFechamento->boletimRg->bairro_quarteirao_id;
    $fechamento->bairro_id = $boletimFechamento->boletimRg->bairro_id;
    $fechamento->lira = $boletimFechamento->imovel_lira == true ? '1' : '0';
    $fechamento->boletim_rg_id =  $boletimFechamento->boletimRg->id;
    $fechamento->data = $boletimFechamento->boletimRg->data;
    $fechamento->quantidade = $boletimFechamento->quantidade;
    $fechamento->imovel_tipo_id = $boletimFechamento->imovel_tipo_id;
    $fechamento->save();
}


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
