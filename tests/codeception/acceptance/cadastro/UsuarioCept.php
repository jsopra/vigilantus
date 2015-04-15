<?php

use \Phactory;

$eu = new \tests\TesterDeAceitacao($scenario);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$eu->quero('verificar que o CRUD de usuários funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clicoNoMenu(['Sistema', 'Usuários']);
$eu->espero('que a listagem inclua os pré-cadastrados');
$eu->vejo('perspectiva', '.grid-view');

$eu->vejo('Chapecó/SC');

$eu->espero('cadastrar um usuário');
$eu->clico('Cadastrar Usuário');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Cadastrar Usuário');
$eu->selecionoOpcao('Nível do Usuário', 'Administrador');
$eu->preenchoCampo('Nome', 'User McTester');
$eu->preenchoCampo('Login', 'mctester');
$eu->preenchoCampo('E-mail', 'mctester@perspectiva.in');
$eu->preenchoCampo('Senha', 'senhadificil');
$eu->preenchoCampo('Repita a senha', 'senhadificil');
$eu->clico('Cadastrar', '#modal-window');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um usuário');
$eu->clicoNoGrid('administrador', 'Alterar');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Atualizar Usuário');
$eu->preenchoCampo('Login', 'adminx');
$eu->preenchoCampo('Senha', 'senhadificil');
$eu->preenchoCampo('Repita a senha', 'senhadificil');
$eu->preenchoCampo('Nome', 'CorredorX');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

// $eu->espero('ver registro de Chapecó');
// $eu->clico('Cadastro');
// $eu->clico('Bairros');

// $eu->espero('cadastrar um bairro');
// $eu->clico('Cadastrar Bairro');
// $eu->aguardoPor(1);
// $eu->vejoUmTitulo('Cadastrar Bairro');
// $eu->selecionoOpcao('Categoria de Bairro', 'Rural');
// $eu->preenchoCampo('Nome', 'Creeeim');
// $eu->clico('Cadastrar', '#modal-window');
// $eu->aguardoPor(1);
// $eu->vejo('O cadastro foi realizado com sucesso.');

// $eu->vejo('Creeeim');
// $eu->naoVejo('Chaves loco');

// $eu->clico('Logout (adminx)');
// $eu->aguardoPor(1);
// $eu->facoLoginComo('root', 'root');

// $eu->quero('Verificar que combo de usuario funciona');

// $eu->vejo('Chapecó/SC');
// $eu->selecionoOpcao('user_municipio', 'Tapera/RS');
// $eu->aguardoPor(1);
// $eu->vejo('Tapera/RS');
// $eu->vejo('Município alterado com sucesso');

// $eu->espero('ver registro de Tapera');
// $eu->clico('Cadastro');
// $eu->aguardoPor(1);
// $eu->clico('Categoria de Bairros');

// $eu->espero('cadastrar uma categoria de bairro');
// $eu->clico('Cadastrar Categoria de Bairro');
// $eu->aguardoPor(1);
// $eu->vejoUmTitulo('Cadastrar Categoria de Bairro');
// $eu->preenchoCampo('Nome', 'Locuratudolocura');
// $eu->clico('Cadastrar', '#modal-window');
// $eu->aguardoPor(1);
// $eu->vejo('O cadastro foi realizado com sucesso.');
// $eu->aguardoPor(1);
// $eu->clico('Cadastro');
// $eu->clico('Bairros');

// $eu->clico('Cadastrar Bairro');
// $eu->vejoUmTitulo('Cadastrar Bairro');
// $eu->aguardoPor(1);
// $eu->preenchoCampo('Nome', 'Chaves loco');
// $eu->selecionoOpcao('Categoria de Bairro', 'Locuratudolocura');
// $eu->clico('Cadastrar', '#modal-window');
// $eu->aguardoPor(1);
// $eu->vejo('O cadastro foi realizado com sucesso.');
// $eu->vejo('Chaves loco');

//$scenario->incomplete('ver porque a linha abaixo falha.. o teste nao estah claro');
// $eu->naoVejo('Creeeim');

//$eu->clico('Logout (root)');

// $eu->aguardoPor(1);
// LoginPage::openBy($eu)->login('mctester', 'senhadificil');
// $eu->vejo('Logout (mctester)');

// $eu->espero('excluir um usuário');
// $eu->clico('Sistema');
// $eu->clico('Usuários');
// $eu->clicoNoGrid('adminx', 'Excluir');
// $eu->vejoNaPopUp('Confirma a exclusão deste item?');
// $eu->aceitoPopUp();
// $eu->aguardoPor(1);
// $eu->naoVejo('adminx');
