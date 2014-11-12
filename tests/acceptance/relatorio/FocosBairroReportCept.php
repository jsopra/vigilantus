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

$eu->quero('verificar que o detalhamento de focos por bairro funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->aguardoPor(1);

$eu->espero('ver os dados na home');

$eu->clicoNoMenu(['Relatórios', 'Focos por Bairro']);

$eu->vejo('Bairro 0001');
$eu->vejo('Bairro 0002');
$eu->vejo('Bairro 0003');

$eu->clico("//a[@title='Ver Focos']");

$eu->aguardoPor(1);

$eu->vejo(implode(' ', ['1', '0001', 'Vinculado à Quarteirão', 'TD0001', 'Aedes_0001']));
