<?php

use \Phactory;

if ($this->scenario->running()) {
    Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
}

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD de bairros funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Localização');
$eu->clico('Bairros e Quarteirões');

$eu->espero('cadastrar um bairro');
$eu->clico('Cadastrar Bairro');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Cadastrar Bairro');
$eu->selecionoOpcao('Categoria', 'Rural');
$eu->preenchoCampo('Nome', 'Passo de los Fuertes');
$eu->executeJs('$(\'#bairro-coordenadasjson\').val(\'[{"A":"-27.090154283676","k":"-52.620005607605"},{"A":"-27.088855233117","k":"-52.617774009705"},{"A":"-27.091778075689","k":"-52.613772153854"},{"A":"-27.091797178985","k":"-52.615638971329"},{"A":"-27.10027872022","k":"-52.602034807205"},{"A":"-27.101921467004","k":"-52.612849473953"},{"A":"-27.100698960064","k":"-52.62481212616"},{"A":"-27.089008063376","k":"-52.625670433044"},{"A":"-27.085760375501","k":"-52.622172832489"},{"A":"-27.084996199966","k":"-52.621314525604"},{"A":"-27.087212294659","k":"-52.621014118195"},{"A":"-27.090154283676","k":"-52.620005607605"}]\');');
$eu->clico('Cadastrar', '.modal');
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
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Passo dos Fortes');
