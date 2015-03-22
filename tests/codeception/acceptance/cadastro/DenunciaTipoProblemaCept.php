<?php
use \Phactory;
use app\models\Cliente;

$eu = new TesterDeAceitacao($scenario);

$usuario = Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
$modulo = Phactory::modulo(['id' => 1, 'nome' => 'Denuncias']);
$cliente = Cliente::find()->andWhere('id=1')->one();
Phactory::clienteModulo(['cliente_id' => $cliente, 'modulo_id' => $modulo]);
Phactory::bairro(['nome' => 'Seminário', 'cliente_id' => $cliente, 'bairro_categoria_id' => 1]);

$eu->quero('verificar que o CRUD De Tipo de Problema em Denúncia funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastros');
$eu->clico('Tipo de Problema em Denúncia');

$eu->espero('cadastrar um Tipo de Problema');
$eu->clico('Cadastrar Tipo de Problema');
$eu->aguardoPor(1);
$eu->preenchoCampo('Nome', 'Problema A');
$eu->clico('Cadastrar', '#modal-window');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um Tipo de Problema');
$eu->clicoNoGrid('Problema A', 'Alterar');
$eu->aguardoPor(1);
$eu->preenchoCampo('Nome', 'Problema Z');
$eu->clico('Atualizar', '#modal-window');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');

$eu->espero('excluir um Denuncias');
$eu->clicoNoGrid('Problema Z', 'Excluir');
$eu->vejoNaPopUp('Confirma a exclusão deste item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('Problema Z');
