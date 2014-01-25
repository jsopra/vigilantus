<?php

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD de categorias de bairro funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastro');
$eu->clico('Categoria de Bairros');
$eu->espero('que a listagem inclua os pré-cadastrados');
$eu->vejo('Urbano');
$eu->vejo('Rural');

$eu->espero('cadastrar uma categoria de bairro');
$eu->clico('Cadastrar Categoria de Bairro');
$eu->vejoNoTitulo('Cadastrar Categoria de Bairro');
$eu->selecionoOpcao('Município', 'Chapecó');
$eu->preenchoCampo('Nome', 'Espacial');
$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar uma categoria de bairro');
$eu->clicoNoGrid('Espacial', 'Atualizar');
$eu->vejoNoTitulo('Atualizar Categoria de Bairro');
$eu->selecionoOpcao('Município', 'Tapera');
$eu->preenchoCampo('Nome', 'Bairro Aéreo');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir uma categoria de bairro');
$eu->clicoNoGrid('Bairro Aéreo', 'Excluir');
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Bairro Aéreo');
