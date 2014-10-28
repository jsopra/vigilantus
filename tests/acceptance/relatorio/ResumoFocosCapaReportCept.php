<?php

use app\models\ImovelTipo;
use app\models\Municipio;
use \Phactory;

$eu = new TesterDeAceitacao($scenario);

$usuario = Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$municipio = Municipio::find()->one();

if (!$municipio) {
    $municipio = Phactory::municipio();
}

Phactory::bairro(['municipio_id' => 1]);
$bairroB = Phactory::bairro(['municipio_id' => 1]);
$bairroC = Phactory::bairro(['municipio_id' => 1]);

$quarteiraoB = Phactory::bairroQuarteirao([
    'municipio_id' => 1,
    'bairro_id' => $bairroB->id,
]);

$quarteiraoC = Phactory::bairroQuarteirao([
    'municipio_id' => 1,
    'bairro_id' => $bairroC->id,
]);

$especieA = Phactory::especieTransmissor(['municipio_id' => 1]);
$especieB = Phactory::especieTransmissor(['municipio_id' => 1]);

$depositoA = Phactory::depositoTipo(['municipio_id' => 1]);
$depositoB = Phactory::depositoTipo(['municipio_id' => 1]);
$depositoC = Phactory::depositoTipo(['municipio_id' => 1]);

$data = date('01/04/Y');

Phactory::focoTransmissor([
    'tipo_deposito_id' => $depositoA->id,
    'bairro_quarteirao_id' => $quarteiraoB->id, 
    'especie_transmissor_id' => $especieA->id,
    'data_entrada' => $data,
    'quantidade_ovos' => 2,
    'quantidade_forma_adulta' => 2,
    'quantidade_forma_aquatica' => 2,
]);

Phactory::focoTransmissor([
    'tipo_deposito_id' => $depositoB->id,
    'bairro_quarteirao_id' => $quarteiraoB->id, 
    'especie_transmissor_id' => $especieB->id,
    'data_entrada' => $data,
    'quantidade_ovos' => 2,
    'quantidade_forma_adulta' => 2,
    'quantidade_forma_aquatica' => 2,
]);

Phactory::focoTransmissor([
    'tipo_deposito_id' => $depositoC->id,
    'bairro_quarteirao_id' => $quarteiraoC->id, 
    'especie_transmissor_id' => $especieB->id,
    'data_entrada' => $data,
    'quantidade_ovos' => 0,
    'quantidade_forma_adulta' => 2,
    'quantidade_forma_aquatica' => 0,
]);

$eu->quero('verificar que a capa de focos funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->aguardoPor(1);

$eu->espero('ver os dados na home');

$eu->clico('a[href="#focos"]');

$eu->vejo(implode(' ', ['TD0001', '1', '100', '0', '0']));
$eu->vejo(implode(' ', ['TD0002', '0', '0', '1', '50']));
$eu->vejo(implode(' ', ['TD0003', '0', '0', '1', '50']));

$eu->vejo(implode(' ', ['Aquática', '1', '100', '1', '50']));
$eu->vejo(implode(' ', ['Adulta', '1', '100', '2', '100']));
$eu->vejo(implode(' ', ['Ovos', '1', '100', '1', '50']));