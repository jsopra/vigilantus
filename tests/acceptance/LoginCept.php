<?php

use tests\_pages\LoginPage;

$I = new WebGuy($scenario);
$I->wantTo('verificar se o login funciona');

$loginPage = LoginPage::openBy($I);

$I->see('Login', 'h1');

$I->amGoingTo('tentar logar sem login e senha');
$loginPage->login('', '');
$I->expectTo('ver erros de validação');
$I->see('“Usuário” não pode ficar em branco.');
$I->see('“Senha” não pode ficar em branco.');

$I->amGoingTo('tentar logar com senha inválida');
$loginPage->login('administrador', 'senhaerrada');
$I->expectTo('ver erros de validação');
$I->see('Usuário ou senha inválida.');

$I->amGoingTo('tentar logar com dados válidos');
$loginPage->login('administrador', 'administrador');
if (method_exists($I, 'wait')) {
	$I->wait(3);
}
$I->expectTo('ver as informações do usuário');
$I->see('Logout (administrador)');
