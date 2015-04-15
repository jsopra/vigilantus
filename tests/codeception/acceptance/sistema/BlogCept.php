<?php
use \Phactory;

$eu = new \tests\TesterDeAceitacao($scenario);

Phactory::blogPost();

$eu->quero('verificar que o blog funciona');
$eu->estouNaPagina(Yii::$app->homeUrl);
$eu->vejo('Vigilantus');
$eu->vejoLink('Blog');
$eu->clico('Blog');
$eu->vejo('Post A');
