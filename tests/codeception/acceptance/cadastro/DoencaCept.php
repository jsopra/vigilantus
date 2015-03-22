<?php
use \Phactory;

$eu = new TesterDeAceitacao($scenario);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$eu->quero('verificar que o CRUD Doencas funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastros');
$eu->clico('Doenças');

$eu->espero('cadastrar uma Doença');
$eu->clico('Cadastrar Doença');
$eu->aguardoPor(1);
$eu->preenchoCampo('Nome', 'Dengue');
$eu->clico('Cadastrar', '#modal-window');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar uma Doença');
$eu->clicoNoGrid('Dengue', 'Alterar');
$eu->aguardoPor(1);
$eu->preenchoCampo('Nome', 'Chagas');
$eu->clico('Atualizar', '#modal-window');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um Doencas');
$eu->clicoNoGrid('Chagas', 'Excluir');
$eu->vejoNaPopUp('Confirma a exclusão deste item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Chagas');
