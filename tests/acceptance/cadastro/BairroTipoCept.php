<?php

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD de tipos de bairro funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastro');
$eu->clico('Tipos de Bairros');
$eu->espero('que a listagem inclua os pré-cadastrados');
$eu->vejo('Urbano');
$eu->vejo('Rural');

$eu->espero('cadastrar um tipo de bairro');
$eu->clico('Cadastrar Tipo de Bairro');
$eu->vejoNoTitulo('Cadastrar Tipo de Bairro');
$eu->selecionoOpcao('Município', 'Chapecó');
$eu->preenchoCampo('Nome', 'Espacial');
$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um tipo de bairro');
$eu->clicoNoGrid('Espacial', 'Atualizar');
$eu->vejoNoTitulo('Atualizar Tipo de Bairro');
$eu->selecionoOpcao('Município', 'Tapera');
$eu->preenchoCampo('Nome', 'Bairro Aéreo');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um tipo de bairro');
$eu->clicoNoGrid('Bairro Aéreo', 'Excluir');
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Bairro Aéreo');
