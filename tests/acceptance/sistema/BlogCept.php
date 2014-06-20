<?php

if ($this->scenario->running()) {
    Phactory::blogPost();
}

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o blog funciona');
$eu->estouNaPagina(Yii::$app->homeUrl);
$eu->vejo('Vigilantus');
$eu->vejoLink('Blog');
$eu->clico('Blog');
$eu->vejo('Post A');
