<?php

use \Phactory;
use app\models\Cliente;

$eu = new TesterDeAceitacao($scenario);

$usuario = Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
$modulo = Phactory::modulo(['id' => 1, 'nome' => 'Denuncias']);
$cliente = Cliente::find()->andWhere('id=1')->one();

$clienteSegundo = Phactory::cliente(['municipio_id' => 2]);

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

$eu->vejo('Passo de los Fuertes');

$eu->estouNaPagina(Yii::$app->homeUrl . '?r=site/session&id=' . $clienteSegundo->id);

$eu->espero('não ver bairros de outro cliente');
$eu->clicoNoMenu(['Localização', 'Bairros e Quarteirões']);
$eu->aguardoPor(1);

$eu->naoVejo('Passo de los Fuertes');
