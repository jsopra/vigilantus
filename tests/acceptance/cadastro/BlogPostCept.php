<?php
use \Phactory;

if ($this->scenario->running()) {
    Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
}


$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD Blog Posts funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Blog Posts');

$eu->espero('cadastrar um Blog Posts');
$eu->clico('Cadastrar Post');
$eu->aguardoPor(1);
$eu->preenchoCampo('Título', 'Dengue em Santa Catarina');
$eu->executeJs("CKEDITOR.instances['blogpost-texto'].setData('<p>testContent</p>');");
$eu->clico('Cadastrar');
$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um Blog Posts');
$eu->clicoNoGrid('Dengue em Santa Catarina', 'Alterar'); 
$eu->aguardoPor(1);
$eu->preenchoCampo('Título', 'Dengue no Rio Grande do Sul');
$eu->clico('Atualizar');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um Blog Posts');
$eu->clicoNoGrid('Dengue no Rio Grande do Sul', 'Excluir');
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Dengue no Rio Grande do Sul');