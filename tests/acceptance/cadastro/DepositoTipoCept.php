<?php

use \Phactory;

if ($this->scenario->running()) {
    Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
}

$scenario->incomplete('falta escrever os testes');

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD Deposito Tipos funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastro');
$eu->clico('Tipos de Depósitos');

$eu->espero('cadastrar um tipo de depósito');
$eu->clico('Cadastrar Tipo de Depósito');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Cadastrar Tipo de Depósito');

//@TODO preencher form

$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um Deposito Tipos');
$eu->clicoNoGrid('XXX Registro', 'Alterar'); //@todo
$eu->aguardoPor(1);
$eu->vejoNoTitulo('Atualizar XXX Titulo'); //@todo
//@TODO preencher form
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um Deposito Tipos');
$eu->clicoNoGrid('XXX Registro', 'Excluir'); //@todo
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('XXX Registro'); //@todo