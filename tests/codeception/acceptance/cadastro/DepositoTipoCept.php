<?php

use \Phactory;

$eu = new \tests\TesterDeAceitacao($scenario);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$eu->quero('verificar que o CRUD Deposito Tipos funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clicoNoMenu(['Cadastros', 'Tipos de Depósitos']);

$eu->espero('cadastrar um tipo de depósito');
$eu->clico('Cadastrar Tipo de Depósito');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Cadastrar Tipo de Depósito');

$eu->preenchoCampo('Descrição', 'Tipo UM');
$eu->preenchoCampo('Sigla', 'TU');

$eu->clico('Cadastrar', '#modal-window');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um tipo de depósito');
$eu->clicoNoGrid('Tipo UM', 'Alterar');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Atualizar Tipo de Depósito'); 
$eu->preenchoCampo('Descrição', 'TipoDOIS');
$eu->preenchoCampo('Sigla', 'TD');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('cadastrar um tipo de depósito filho');
$eu->clico('Cadastrar Tipo de Depósito');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Cadastrar Tipo de Depósito');

$eu->preenchoCampo('Descrição', 'Tipo FILHO');
$eu->preenchoCampo('Sigla', 'TF');
$eu->selecionoOpcao('Tipo de Depósito Pai', 'TipoDOIS');

$eu->clico('Cadastrar', '#modal-window');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('excluir um Deposito Tipos');
$eu->clicoNoGrid('Tipo FILHO', 'Excluir');
$eu->vejoNaPopUp('Confirma a exclusão deste item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Tipo FILHO Registro');
