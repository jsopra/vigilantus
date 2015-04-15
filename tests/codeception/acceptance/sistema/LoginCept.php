<?php

use \Phactory;
use tests\_pages\LoginPage;

$eu = new \tests\TesterDeAceitacao($scenario);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$eu->quero('verificar se o login funciona');

$paginaDeLogin = LoginPage::openBy($eu);

$eu->vejo('Login', 'h1');

$eu->vou('tentar logar sem login e senha');
$paginaDeLogin->login('', '');
$eu->espero('ver erros de validação');
$eu->vejo('"Usuário" não pode ficar em branco.');
$eu->vejo('"Senha" não pode ficar em branco.');

$eu->vou('tentar logar com senha inválida');
$paginaDeLogin->login('administrador', 'senhaerrada');
$eu->espero('ver erros de validação');
$eu->vejo('Usuário ou senha inválida.');

$eu->vou('tentar logar com dados válidos');
$paginaDeLogin->login('administrador', 'administrador');
$eu->espero('ver as informações do usuário');
$eu->vejo('Logout (administrador)');
