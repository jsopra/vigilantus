<?php
use \Phactory;

$eu = new TesterDeAceitacao($scenario);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$eu->quero('verificar que o CRUD Clientes funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Sistema');
$eu->clico('Clientes');

$eu->espero('cadastrar um Clientes');
$eu->clico('Cadastrar Cliente');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Cadastrar Cliente');
$eu->markSelect2Option('Município', 'Tapera');
$eu->preenchoCampo('Rótulo', 'novacidade');
$eu->preenchoCampo('Nome do contato', 'nome do contato');
$eu->preenchoCampo('Email do contato', 'emaildocontato@gmail.com');
$eu->preenchoCampo('Telefone do contato', '(49) 33160928');
$eu->preenchoCampo('Departamento do contato', 'depto do contato');
$eu->preenchoCampo('Cargo do contato', 'cargo do contato');
$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um Clientes');
$eu->clicoNoGrid('Tapera', 'Alterar');
$eu->aguardoPor(1);
$eu->preenchoCampo('Rótulo', 'velhacidade');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um Clientes');
$eu->clicoNoGrid('Tapera', 'Excluir');
$eu->vejoNaPopUp('Confirma a exclusão deste item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Tapera');
