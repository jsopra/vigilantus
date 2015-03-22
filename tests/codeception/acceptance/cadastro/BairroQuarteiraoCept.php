<?php

use \Phactory;

$eu = new TesterDeAceitacao($scenario);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
Phactory::bairro(['nome' => 'Seminário', 'cliente_id' => 1]);

$eu->quero('verificar que o CRUD de quarteirões funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clicoNoMenu(['Localização', 'Bairros e Quarteirões']);

$eu->clico("//a[@title='Gerenciar Quarteirões do Bairro Seminário']");
$eu->aguardoPor(1);

$eu->vejoUmTitulo('Quarteirões do Bairro "Seminário"');

$eu->espero('cadastrar um quarteirão');
$eu->clico('Cadastrar Quarteirão de Bairro');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Cadastrar Quarteirão do Bairro "Seminário"');
$eu->preenchoCampo('Número principal', '156');
$eu->preenchoCampo('Número alternativo', '418');
$eu->executeJs('$(\'#bairroquarteirao-coordenadasjson\').val(\'[{"A":"-27.090154283676","k":"-52.620005607605"},{"A":"-27.088855233117","k":"-52.617774009705"},{"A":"-27.091778075689","k":"-52.613772153854"},{"A":"-27.091797178985","k":"-52.615638971329"},{"A":"-27.10027872022","k":"-52.602034807205"},{"A":"-27.101921467004","k":"-52.612849473953"},{"A":"-27.100698960064","k":"-52.62481212616"},{"A":"-27.089008063376","k":"-52.625670433044"},{"A":"-27.085760375501","k":"-52.622172832489"},{"A":"-27.084996199966","k":"-52.621314525604"},{"A":"-27.087212294659","k":"-52.621014118195"},{"A":"-27.090154283676","k":"-52.620005607605"}]\');');
$eu->clico('Cadastrar', '#modal-window');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um quarteirão');
$eu->clicoNoGrid('418', 'Alterar');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Atualizar Quarteirão do Bairro: "Seminário"');
$eu->preenchoCampo('Número principal', '777');
$eu->preenchoCampo('Número alternativo', '');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um quarteirão');
$eu->clicoNoGrid('777', 'Excluir');
$eu->vejoNaPopUp('Confirma a exclusão deste item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('777');
