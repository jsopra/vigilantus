<?php

use \Phactory;
use tests\_pages\IndexPage;
$eu = new \tests\TesterDeAceitacao($scenario);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);

$eu->quero('enviar um feedback');
$eu->facoLoginComo('administrador', 'administrador');

$pagina = IndexPage::openBy($eu);

$eu->vejo('Feedback');

$eu->clico('Feedback');

$eu->aguardoPor(1);

$eu->vejo('Envie comentários, ideias, problemas, ...');

$pagina->submitFeedback(['body' => '']);

$eu->espero('ver erros de validação');

$eu->vejo('Erro ao enviar mensagem');

$eu->vou('enviar o form com um motivo');

$pagina->submitFeedback(['body' => 'test content']);

$eu->aguardoPor(1);

$eu->vejo('Feedback enviado com sucesso');

$eu->vejo('Enviar');

$eu->vejo('Envie comentários, ideias, problemas, ...');

$eu->aguardoPor(1);

$eu->clico('Cancelar');

$eu->aguardoPor(1);

$eu->naoVejo('Envie comentários, ideias, problemas, ...');
