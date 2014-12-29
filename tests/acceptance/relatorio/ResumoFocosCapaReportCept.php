<?php

use app\models\ImovelTipo;
use \Phactory;
use app\models\Cliente;

$eu = new TesterDeAceitacao($scenario);

$cliente = Cliente::find()->andWhere('id=1')->one();

$eu = new TesterDeAceitacao($scenario);

$usuario = Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

Phactory::bairro(['cliente_id' => $cliente]);
$bairroB = Phactory::bairro(['cliente_id' => $cliente]);
$bairroC = Phactory::bairro(['cliente_id' => $cliente]);

$quarteiraoB = Phactory::bairroQuarteirao([
    'cliente_id' => $cliente,
    'bairro_id' => $bairroB->id,
]);

$quarteiraoC = Phactory::bairroQuarteirao([
    'cliente_id' => $cliente,
    'bairro_id' => $bairroC->id,
]);

$especieA = Phactory::especieTransmissor(['cliente_id' => $cliente]);
$especieB = Phactory::especieTransmissor(['cliente_id' => $cliente]);

$depositoA = Phactory::depositoTipo(['cliente_id' => $cliente]);
$depositoB = Phactory::depositoTipo(['cliente_id' => $cliente]);
$depositoC = Phactory::depositoTipo(['cliente_id' => $cliente]);

$data = date('01/04/Y');

Phactory::focoTransmissor([
    'tipo_deposito_id' => $depositoA->id,
    'bairro_quarteirao_id' => $quarteiraoB->id,
    'especie_transmissor_id' => $especieA->id,
    'data_entrada' => $data,
    'quantidade_ovos' => 2,
    'quantidade_forma_adulta' => 2,
    'quantidade_forma_aquatica' => 2,
    'cliente_id' => $cliente
]);

Phactory::focoTransmissor([
    'tipo_deposito_id' => $depositoB->id,
    'bairro_quarteirao_id' => $quarteiraoB->id,
    'especie_transmissor_id' => $especieB->id,
    'data_entrada' => $data,
    'quantidade_ovos' => 2,
    'quantidade_forma_adulta' => 2,
    'quantidade_forma_aquatica' => 2,
    'cliente_id' => $cliente
]);

Phactory::focoTransmissor([
    'tipo_deposito_id' => $depositoC->id,
    'bairro_quarteirao_id' => $quarteiraoC->id,
    'especie_transmissor_id' => $especieB->id,
    'data_entrada' => $data,
    'quantidade_ovos' => 0,
    'quantidade_forma_adulta' => 2,
    'quantidade_forma_aquatica' => 0,
    'cliente_id' => $cliente
]);

$eu->quero('verificar que a capa de focos funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->aguardoPor(1);

$eu->espero('ver os dados na home');

$eu->clico('li[id="focos"] a');

$eu->vejo(implode(' ', ['TD0001', '1', '100', '0', '0']));
$eu->vejo(implode(' ', ['TD0002', '0', '0', '1', '50']));
$eu->vejo(implode(' ', ['TD0003', '0', '0', '1', '50']));

$eu->vejo(implode(' ', ['Aquática', '1', '100', '1', '50']));
$eu->vejo(implode(' ', ['Adulta', '1', '100', '2', '100']));
$eu->vejo(implode(' ', ['Ovos', '1', '100', '1', '50']));
