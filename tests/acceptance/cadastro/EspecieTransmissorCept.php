<?php

use \Phactory;

if ($this->scenario->running()) {
    Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
}

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD de espécies de transmissoress funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastro');
$eu->clico('Espécies de Transmissores');

$eu->espero('cadastrar uma espécie de transmissor');
$eu->clico('Cadastrar Espécie de Transmissor');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Cadastrar Espécie de Transmissor');
$eu->preenchoCampo('Nome', 'Aedes aegypti');
$eu->clico('Cadastrar', '.modal');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar uma espécie de transmissor');
$eu->clicoNoGrid('Aedes aegypti', 'Alterar');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Atualizar Espécie de Transmissor');
$eu->preenchoCampo('Nome', 'Aedes albopictus');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir uma espécie de transmissor');
$eu->clicoNoGrid('Aedes albopictus', 'Excluir');
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Aedes albopictus');
