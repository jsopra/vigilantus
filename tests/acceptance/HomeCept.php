<?php

$I = new WebGuy($scenario);
$I->wantTo('verificar que a home funciona');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('Vigilantus');
$I->seeLink('Login');
$I->click('Login');
$I->see('Continuar conectado');
