<?php

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD de bairros funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastro');
$eu->clico('Bairros');

$eu->espero('cadastrar um bairro');
$eu->clico('Cadastrar Bairro');
$eu->vejoNoTitulo('Cadastrar Bairro');
$eu->selecionoOpcao('Município', 'Tapera');
$eu->selecionoOpcao('Categoria de Bairro', 'Rural');
$eu->preenchoCampo('Nome', 'Passo de los Fuertes');
$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um bairro');
$eu->clicoNoGrid('Passo de los Fuertes', 'Atualizar');
$eu->vejoNoTitulo('Atualizar Bairro');
$eu->selecionoOpcao('Município', 'Chapecó');
$eu->selecionoOpcao('Categoria de Bairro', 'Urbano');
$eu->preenchoCampo('Nome', 'Passo dos Fortes');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um bairro');
$eu->clicoNoGrid('Passo dos Fortes', 'Excluir');
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Passo dos Fortes');
