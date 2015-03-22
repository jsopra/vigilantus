<?php

use app\models\ImovelTipo;
use \Phactory;

use app\models\Cliente;

$eu = new TesterDeAceitacao($scenario);

$cliente = Cliente::find()->andWhere('id=1')->one();

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
   'cliente_id' => $cliente,
]);

Phactory::focoTransmissor([
   'tipo_deposito_id' => $depositoB->id,
   'bairro_quarteirao_id' => $quarteiraoB->id,
   'especie_transmissor_id' => $especieB->id,
   'data_entrada' => $data,
   'quantidade_ovos' => 2,
   'quantidade_forma_adulta' => 2,
   'quantidade_forma_aquatica' => 2,
   'cliente_id' => $cliente,
]);

Phactory::focoTransmissor([
   'tipo_deposito_id' => $depositoC->id,
   'bairro_quarteirao_id' => $quarteiraoC->id,
   'especie_transmissor_id' => $especieB->id,
   'data_entrada' => $data,
   'quantidade_ovos' => 0,
   'quantidade_forma_adulta' => 2,
   'quantidade_forma_aquatica' => 0,
   'cliente_id' => $cliente,
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
