<?php
use \Phactory;

$eu = new \tests\TesterDeAceitacao($scenario);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$eu->quero('verificar que o CRUD Configuracao Clientes funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Sistema');
$eu->clico('Configurações');

$eu->espero('editar um Configuracao Clientes');
$eu->clicoNoGrid('Qtde. dias foco público', 'Alterar');
$eu->aguardoPor(1);
$eu->preenchoCampo('Valor', '75');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');
