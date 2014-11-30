<?php
use \Phactory;

$eu = new TesterDeAceitacao($scenario);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$eu->quero('verificar que o CRUD Módulos funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Sistema');
$eu->clico('Módulos');

$eu->espero('cadastrar um Módulo');
$eu->clico('Cadastrar Modulo');
$eu->aguardoPor(1);
$eu->preenchoCampo('Nome', 'Denúncia');
$eu->clico('Cadastrar', '#modal-window');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um Módulo');
$eu->clicoNoGrid('Denúncia', 'Alterar');
$eu->aguardoPor(1);
$eu->preenchoCampo('Nome', 'Denúncias');
$eu->clico('Atualizar', '#modal-window');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um Módulo');
$eu->clicoNoGrid('Denúncias', 'Excluir');
$eu->vejoNaPopUp('Confirma a exclusão deste item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Denúncias');
