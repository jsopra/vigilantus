<?php

use \Phactory;

$eu = new TesterDeAceitacao($scenario);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$eu->quero('verificar que o CRUD de bairros funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clicoNoMenu(['Localização', 'Bairros e Quarteirões']);

$eu->espero('cadastrar um bairro');
$eu->clico('Cadastrar Bairro');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Cadastrar Bairro');
$eu->selecionoOpcao('Categoria', 'Rural');
$eu->preenchoCampo('Nome', 'Passo de los Fuertes');
$eu->executeJs('$(\'#bairro-coordenadasjson\').val(\'[{"k":-27.086639170923544,"B":-52.616631388664246},{"k":-27.08824390999381,"B":-52.61819779872894},{"k":-27.08841584495935,"B":-52.614378333091736}]\');');
$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um bairro');
$eu->clicoNoGrid('Passo de los Fuertes', 'Alterar');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Atualizar Bairro');
$eu->selecionoOpcao('Categoria', 'Urbano');
$eu->preenchoCampo('Nome', 'Passo dos Fortes');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um bairro');
$eu->clicoNoGrid('Passo dos Fortes', 'Excluir');
$eu->vejoNaPopUp('Confirma a exclusão deste item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Passo dos Fortes');
