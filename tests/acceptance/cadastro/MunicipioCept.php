<?php
use \Phactory;

if ($this->scenario->running()) {
    Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
}


$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD Municipios funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastro');
$eu->clico('XXX Item de menu do CRUD'); //@todo

$eu->espero('cadastrar um Municipios');
$eu->clico('Cadastrar XXX Item do CRUD'); //@todo
$eu->aguardoPor(1);
//@TODO preencher form
$eu->clico('Cadastrar', '.modal');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um Municipios');
$eu->clicoNoGrid('XXX Registro', 'Alterar'); //@todo
$eu->aguardoPor(1);
//@TODO preencher form
$eu->clico('Atualizar', '.modal');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um Municipios');
$eu->clicoNoGrid('XXX Registro', 'Excluir'); //@todo
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('XXX Registro'); //@todo