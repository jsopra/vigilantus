<?php

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD de quarteirões funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastro');
$eu->clico('Quarteirões de Bairros');

$eu->espero('cadastrar um quarteirão');
$eu->clico('Cadastrar Quarteirão de Bairro');
$eu->vejoNoTitulo('Cadastrar Quarteirão de Bairro');
$eu->selecionoOpcao('Município', 'Chapecó');
$eu->selecionoOpcao('Bairro', 'Seminário');
$eu->preenchoCampo('Número principal', '156');
$eu->preenchoCampo('Número alternativo', '418');
$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um quarteirão');
$eu->clicoNoGrid('418', 'Atualizar');
$eu->vejoNoTitulo('Atualizar Quarteirão de Bairro');
$eu->preenchoCampo('Número principal', '777');
$eu->preenchoCampo('Número alternativo', '');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um quarteirão');
$eu->clicoNoGrid('777', 'Excluir');
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('777');
