<?php

$eu = new TesterDeAceitacao($scenario);
$eu->quero('verificar que a home funciona');
$eu->estouNaPagina(Yii::$app->homeUrl);
$eu->vejo('Vigilantus');
$eu->vejoLink('Login');
$eu->clico('Login');
$eu->vejo('Continuar conectado');
