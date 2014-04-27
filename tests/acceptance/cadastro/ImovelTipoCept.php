<?php

use \Phactory;

if ($this->scenario->running()) {
    Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
}

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD de tipos de imóveis funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastro');
$eu->clico('Tipos de Imóvel');

$eu->espero('que a listagem inclua os pré-cadastrados');
$eu->vejo('Residencial');
$eu->vejo('Comercial');
$eu->vejo('Terreno Baldio');
$eu->vejo('Pontos Estratégicos');
$eu->vejo('Outros');

$eu->espero('cadastrar um tipo de imóvel');
$eu->clico('Cadastrar Tipo de Imóvel');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Cadastrar Tipo de Imóvel');
$eu->preenchoCampo('Nome', 'Duna');
$eu->preenchoCampo('Sigla', 'DN');
$eu->clico('Cadastrar', '.modal');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um tipo de imóvel');
$eu->clicoNoGrid('Duna', 'Alterar');
$eu->aguardoPor(1);
$eu->vejoUmTitulo('Atualizar Tipo de Imóvel');
$eu->preenchoCampo('Nome', 'Rochedo');
$eu->preenchoCampo('Sigla', '');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um tipo de imóvel');
$eu->clicoNoGrid('Rochedo', 'Excluir');
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Rochedo');
