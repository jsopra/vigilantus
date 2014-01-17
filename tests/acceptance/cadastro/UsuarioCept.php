<?php

use tests\_pages\LoginPage;

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD de usuários funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Sistema');
$eu->clico('Usuários');
$eu->espero('que a listagem inclua os pré-cadastrados');
$eu->vejo('perspectiva', '.grid-view');

$eu->espero('cadastrar um usuário');
$eu->clico('Cadastrar Usuário');
$eu->vejoNoTitulo('Cadastrar Usuário');
$eu->selecionoOpcao('Nível do Usuário', 'Administrador');
$eu->naoVejo('Município', 'form');
$eu->preenchoCampo('Nome', 'User McTester');
$eu->preenchoCampo('Login', 'mctester');
$eu->preenchoCampo('E-mail', 'mctester@perspectiva.in');
$eu->preenchoCampo('Senha', 'senhadificil');
$eu->preenchoCampo('Repita a senha', 'senhadificil');
$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um usuário');
$eu->clicoNoGrid('administrador', 'Atualizar');
$eu->vejoNoTitulo('Atualizar Usuário');
$eu->naoVejo('Município', 'form');
$eu->preenchoCampo('Login', 'adminx');
$eu->preenchoCampo('Senha', 'senhadificil');
$eu->preenchoCampo('Repita a senha', 'senhadificil');
$eu->preenchoCampo('Nome', 'Passo dos Fortes');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('conseguir logar com o novo usuário');
$eu->clico('Logout (adminx)');
$eu->aguardoPor(1);
LoginPage::openBy($eu)->login('mctester', 'senhadificil');
$eu->vejo('Logout (mctester)');

$eu->espero('excluir um usuário');
$eu->clico('Sistema');
$eu->clico('Usuários');
$eu->clicoNoGrid('adminx', 'Excluir');
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('adminx');
