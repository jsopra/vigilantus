<?php

use \Phactory;
use tests\_pages\IndexPage;

if ($this->scenario->running()) {
    
    Phactory::usuario(
        'root',
        [
            'login' => 'administrador',
            'senha' => 'administrador',
            'senha2' => 'administrador', // necessário por causa de falha de design
        ]
    );
}

$eu = new CaraDaWeb($scenario);
$eu->quero('enviar um feedback');
$eu->facoLoginComo('administrador', 'administrador');

$pagina = IndexPage::openBy($eu);

$eu->vejo('Feedback');

$eu->clico('Feedback');

$eu->aguardoPor(1);

$eu->vejo('Envie comentários, ideias, problemas, ...');

$pagina->submitFeedback(['body' => '']);

$eu->espero('ver erros de validação');

$eu->vejo('Você deve escrever alguma coisa');

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
