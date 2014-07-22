<?php
use tests\_pages\IndexPage;

$eu = new TesterDeAceitacao($scenario);

$eu->quero('garantir que o feedback não aparece para usuário offline');

$pagina = IndexPage::openBy($eu);

$eu->vejo('Vigilantus');
$eu->naoVejo('Feedback');
$eu->naoVejoElemento('.feedback-btn');

