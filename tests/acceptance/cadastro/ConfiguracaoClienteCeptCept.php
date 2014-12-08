<?php
use \Phactory;


Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$eu = new TesterDeAceitacao($scenario);
$eu->quero('verificar que o CRUD Configuracao Clientes funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastro');
$eu->clico('XXX Item de menu do CRUD'); //@todo

$eu->espero('cadastrar um Configuracao Clientes');
$eu->clico('Cadastrar XXX Item do CRUD'); //@todo
$eu->aguardoPor(1);
//@TODO preencher form
$eu->clico('Cadastrar', '.modal');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um Configuracao Clientes');
$eu->clicoNoGrid('XXX Registro', 'Alterar'); //@todo
$eu->aguardoPor(1);
//@TODO preencher form
$eu->clico('Atualizar', '.modal');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um Configuracao Clientes');
$eu->clicoNoGrid('XXX Registro', 'Excluir'); //@todo
$eu->vejoNaPopUp('Confirma a exclusão deste item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('XXX Registro'); //@todo
