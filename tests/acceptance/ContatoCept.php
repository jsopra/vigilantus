<?php

use tests\_pages\ContatoPage;

$eu = new CaraDaWeb($scenario);
$eu->quero('verifica que o contato funciona');

$paginaDeContato = ContatoPage::openBy($eu);

$eu->vejo('Contato', 'h1');

$eu->vou('enviar o form de contato sem dados');
$paginaDeContato->submit([]);
$eu->espero('ver erros de validação');
$eu->vejo('Contato', 'h1');
$eu->vejo('“Nome” não pode ficar em branco.');
$eu->vejo('“E-mail” não pode ficar em branco.');
$eu->vejo('“Assunto” não pode ficar em branco.');
$eu->vejo('“Mensagem” não pode ficar em branco.');
$eu->vejo('O código de verificação está incorreto.');

$eu->vou('enviar o form de contato com um e-mail inválido');
$paginaDeContato->submit([
	'name'			=>	'tester',
	'email'			=>	'tester.email',
	'subject'		=>	'test subject',
	'body'			=>	'test content',
	'verifyCode'	=>	'testme',
]);
$eu->espero('ver que o e-mail está errado');
$eu->naoVejo('“Nome” não pode ficar em branco.');
$eu->vejo('“E-mail” não é um endereço de e-mail válido.');
$eu->naoVejo('“Assunto” não pode ficar em branco.');
$eu->naoVejo('“Mensagem” não pode ficar em branco.');
$eu->naoVejo('O código de verificação está incorreto.');

$eu->vou('enviar o form de contato com dados corretos');
$paginaDeContato->submit([
	'name'			=>	'tester',
	'email'			=>	'tester@example.com',
	'subject'		=>	'test subject',
	'body'			=>	'test content',
	'verifyCode'	=>	'testme',
]);
if (method_exists($eu, 'wait')) {
	$eu->wait(3); // only for selenium
}
$eu->vejo('Obrigado por entrar em contato conosco. Responderemos o mais breve possível.');
$eu->naoVejoElemento('#contact-form');
