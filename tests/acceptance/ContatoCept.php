<?php

use tests\_pages\ContatoPage;

$I = new WebGuy($scenario);
$I->wantTo('verifica que o contato funciona');

$contactPage = ContatoPage::openBy($I);

$I->see('Contato', 'h1');

$I->amGoingTo('enviar o form de contato sem dados');
$contactPage->submit([]);
$I->expectTo('ver erros de validação');
$I->see('Contato', 'h1');
$I->see('“Nome” não pode ficar em branco.');
$I->see('“E-mail” não pode ficar em branco.');
$I->see('“Assunto” não pode ficar em branco.');
$I->see('“Mensagem” não pode ficar em branco.');
$I->see('O código de verificação está incorreto.');

$I->amGoingTo('enviar o form de contato com um e-mail inválido');
$contactPage->submit([
	'name'			=>	'tester',
	'email'			=>	'tester.email',
	'subject'		=>	'test subject',
	'body'			=>	'test content',
	'verifyCode'	=>	'testme',
]);
$I->expectTo('ver que o e-mail está errado');
$I->dontSee('“Nome” não pode ficar em branco.');
$I->see('“E-mail” não é um endereço de e-mail válido.');
$I->dontSee('“Assunto” não pode ficar em branco.');
$I->dontSee('“Mensagem” não pode ficar em branco.');
$I->dontSee('O código de verificação está incorreto.');

$I->amGoingTo('enviar o form de contato com dados corretos');
$contactPage->submit([
	'name'			=>	'tester',
	'email'			=>	'tester@example.com',
	'subject'		=>	'test subject',
	'body'			=>	'test content',
	'verifyCode'	=>	'testme',
]);
if (method_exists($I, 'wait')) {
	$I->wait(3); // only for selenium
}
$I->see('Obrigado por entrar em contato conosco. Responderemos o mais breve possível.');
$I->dontSeeElement('#contact-form');
