<?php

use \Phactory;
use app\models\Cliente;
use app\models\Doenca;

$eu = new \tests\TesterDeAceitacao($scenario);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$cliente = Cliente::find()->andWhere('id=1')->one();

$doenca = Phactory::doenca(['cliente_id' => $cliente]);

$eu->quero('verificar que o CRUD de espécies de transmissores funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clicoNoMenu(['Cadastros', 'Espécies de Transmissores']);

$eu->espero('cadastrar uma espécie de transmissor');
$eu->clico('Cadastrar Espécie de Transmissor');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Cadastrar Espécie de Transmissor');
$eu->preenchoCampo('Nome', 'Aedes aegypti');
$eu->preenchoCampo('Área de foco (metros)', '300');
$eu->preenchoCampo('Permanência do foco (dias)', '360');
$eu->markSelect2Option('Doenças', $doenca->nome);
$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar uma espécie de transmissor');
$eu->clicoNoGrid('Aedes aegypti', 'Alterar');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Atualizar Espécie de Transmissor');
$eu->preenchoCampo('Nome', 'Aedes albopictus');
$eu->preenchoCampo('Área de foco (metros)', '10');
$eu->preenchoCampo('Permanência do foco (dias)', '16');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir uma espécie de transmissor');
$eu->clicoNoGrid('Aedes albopictus', 'Excluir');
$eu->vejoNaPopUp('Confirma a exclusão deste item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Aedes albopictus');
