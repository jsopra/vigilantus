<?php

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que a ficha de RG funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Fichas');
$eu->clico('Boletim de RG');

$eu->espero('cadastrar uma ficha');
$eu->clico('Preencher novo Boletim');
$eu->vejoUmTitulo('Preencher Boletim de RG');
$eu->selecionoOpcao('Bairro', 'Seminário');
$eu->aguardoPor(1);
$eu->vejo('Urbano');
$eu->preenchoCampo('Quarteirão', '123');
$eu->preenchoCampo('Seq', '123');
$eu->preenchoCampo('Folha nº', '123');
$eu->preenchoCampo('Data da Coleta', date('d/m/Y'));

$eu->clico('Cadastrar', '.modal');
$eu->aguardoPor(1);
$eu->vejo('“Imoveis” não pode ficar em branco.');