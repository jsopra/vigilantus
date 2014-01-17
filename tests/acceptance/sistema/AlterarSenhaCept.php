<?php

use tests\_pages\LoginPage;

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que consigo alterar minha senha');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Sistema');
$eu->clico('Alterar senha');

$eu->espero('verificar que requer uma nova senha');
$eu->clico('Alterar Senha');
$eu->aguardoPor(1);
$eu->vejo('“Senha” não pode ficar em branco.');

$eu->espero('alterar minha senha');
$eu->preenchoCampo('Senha', 'jasepassaramdelaraia');
$eu->preenchoCampo('Repita a senha', 'jasepassaramdelaraia');
$eu->clico('Alterar Senha');
$eu->aguardoPor(1);
$eu->vejo('Senha alterada com sucesso.');

$eu->espero('conseguir logar com a nova senha');
$eu->clico('Logout (administrador)');
$eu->aguardoPor(1);
LoginPage::openBy($eu)->login('administrador', 'jasepassaramdelaraia');
$eu->vejo('Logout (administrador)');
