<?php
use \Phactory;
use app\models\Cliente;

$eu = new \tests\TesterDeAceitacao($scenario);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$eu->quero('verificar que o CRUD Municipios funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Sistema');
$eu->clico('Municípios');

$eu->espero('cadastrar um Município');
$eu->clico('Cadastrar Municipio');
$eu->aguardoPor(1);
$eu->preenchoCampo('Nome', 'Robervalândia');
$eu->preenchoCampo('Estado Sigla', 'SC');
$eu->preenchoCampo('Coordenadas', '0101000020E6100000EFAEB321FF183BC086AB0320EE4E4AC0');
$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');
