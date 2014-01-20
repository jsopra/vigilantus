<?php
use tests\_pages\IndexPage;

$eu = new CaraDaWeb($scenario);
$eu->quero('enviar um feedback');
$eu->facoLoginComo('administrador', 'administrador');

$pagina = IndexPage::openBy($eu);

$eu->vejo('Feedback');

$eu->clico('Feedback');

$eu->aguardoPor(1);

$eu->vejo('Envie comentários, idéias, problemas, ...');

$pagina->submitFeedback([]);

$eu->espero('ver erros de validação');

$eu->vejo('Você escrever dizer alguma coisa');

$eu->vou('enviar o form com um motivo');

$pagina->submitFeedback(['body' => 'test content']);

//$eu->vejo('Enviando...');

$eu->aguardoPor(3);

$eu->vejo('Feedback enviado com sucesso');

$eu->vejo('Enviar');

$eu->vejo('Envie comentários, idéias, problemas, ...');

$eu->aguardoPor(1);

$eu->clico('Cancelar');

$eu->aguardoPor(1);

$eu->naoVejo('Envie comentários, idéias, problemas, ...');
