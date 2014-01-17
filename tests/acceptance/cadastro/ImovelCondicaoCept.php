<?php

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD de condições de imóveis funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastro');
$eu->clico('Condições de Imóveis');

$eu->espero('que a listagem inclua os pré-cadastrados');
$eu->vejo('Normal');
$eu->vejo('Área de Foco');
$eu->vejo('RG Lira');

$eu->espero('cadastrar uma condição de imóvel');
$eu->clico('Cadastrar Condição de Imóvel');
$eu->vejoNoTitulo('Cadastrar Condição de Imóvel');
$eu->selecionoOpcao('Município', 'Chapecó');
$eu->preenchoCampo('Nome', 'Acabado');
$eu->marcoOpcao('Exibe Nome');
$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar uma condição de imóvel');
$eu->clicoNoGrid('Acabado', 'Atualizar');
$eu->vejoNoTitulo('Atualizar Condição de Imóvel');
$eu->selecionoOpcao('Município', 'Tapera');
$eu->preenchoCampo('Nome', 'Depreciado');
$eu->desmarcoOpcao('Exibe Nome');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir uma condição de imóvel');
$eu->clicoNoGrid('Depreciado', 'Excluir');
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Depreciado');
