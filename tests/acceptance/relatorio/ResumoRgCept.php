<?php

use \Phactory;

use app\models\Cliente;

$eu = new TesterDeAceitacao($scenario);

$cliente = Cliente::find()->andWhere('id=1')->one();

$usuario = Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$bairro = Phactory::bairro(['nome' => 'Seminário', 'cliente_id' => $cliente]);

$quarteirao = Phactory::bairroQuarteirao(
    [
        'numero_quarteirao' => '156',
        'bairro_id' => $bairro->id,
        'cliente_id' => $cliente
    ]
);
$boletim = Phactory::boletimRg(
    [
        'data' => '07/03/1989',
        'bairro_id' => $bairro->id,
        'bairro_quarteirao_id' => $quarteirao->id,
        'cliente_id' => $cliente
    ]
);
$boletim->adicionarImovel('Rua Rio de Janeiro', '176', null, 'Casa 1', 1, false);
$boletim->adicionarImovel('Rua Rio de Janeiro', '176', null, 'Casa 2', 1, false);
$boletim->salvarComImoveis();

$quarteirao2 = Phactory::bairroQuarteirao(
    [
        'numero_quarteirao' => '418',
        'bairro_id' => $bairro->id,
        'cliente_id' => $cliente
    ]
);
$attributes = $boletim->attributes;
$attributes['folha'] = '2';
$attributes['bairro_quarteirao_id'] = $quarteirao2->id;
unset($attributes['id']);
$boletim2 = Phactory::boletimRg($attributes);
$boletim2->adicionarImovel('Rua Rio de Janeiro', '176', null, 'Casa 1', 2, false);
$boletim2->adicionarImovel('Rua Rio de Janeiro', '176', null, 'Casa 2', 2, false);
$boletim2->adicionarImovel('Rua Rio de Janeiro', '176', null, 'Casa 3', 2, false);
$boletim2->salvarComImoveis();

$eu->quero('verificar que o relatório boletim resumo de reconhecimento geográfico funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clicoNoMenu(['Relatórios', 'Resumo de RG por Bairro']);
$eu->selecionoOpcao('Bairro', 'Seminário');
$eu->naoVejo('Exportar planilha');
$eu->clico('Gerar');
$eu->aguardoPor(1);

$eu->espero('ver os dados na planilha');
$eu->vejo(implode(' ', ['156', '2', '0', '0', '0', '0', '2']));
$eu->vejo(implode(' ', ['418', '0', '3', '0', '0', '0', '3']));
$eu->vejo(implode(' ', ['Total:', '2', '3', '0', '0', '0', '5']));
