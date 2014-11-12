<?php

use \Phactory;

$eu = new TesterDeAceitacao($scenario);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
Phactory::bairro(['nome' => 'Seminário', 'municipio_id' => 1, 'bairro_categoria_id' => 1]);
Phactory::bairroQuarteirao([
    'numero_quarteirao' => '123',
    'municipio_id' => 1,
    'bairro_id' => 1,
]);

$eu->quero('verificar que a ficha de RG funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clicoNoMenu(['Localização', 'Boletim de RG']);

$eu->espero('cadastrar uma ficha');
$eu->clico('Novo Boletim/Fechamento');
$eu->vejoUmTitulo('Preencher Fechamento de Boletim de RG');
$eu->selecionoOpcao('Bairro', 'Seminário');
$eu->aguardoPor(1);
$eu->vejo('Urbano');
$eu->selecionoOpcao('Quarteirão', '123');
$eu->preenchoCampo('Folha nº', '123');
$eu->preenchoCampo('Data da Coleta', date('d/m/Y'));

$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('Nenhum fechamento salvo');

$eu->preenchoCampo("//div[@class='bairro-tipo-form']//input[@name='BoletimRg[fechamentos][1][lira]']", '1');
$eu->preenchoCampo("//div[@class='bairro-tipo-form']//input[@name='BoletimRg[fechamentos][1][nao_lira]']", '0');

$eu->preenchoCampo("//div[@class='bairro-tipo-form']//input[@name='BoletimRg[fechamentos][2][lira]']", '5');
$eu->preenchoCampo("//div[@class='bairro-tipo-form']//input[@name='BoletimRg[fechamentos][2][nao_lira]']", '0');

$eu->preenchoCampo("//div[@class='bairro-tipo-form']//input[@name='BoletimRg[fechamentos][3][lira]']", '10');
$eu->preenchoCampo("//div[@class='bairro-tipo-form']//input[@name='BoletimRg[fechamentos][3][nao_lira]']", '1');

$eu->preenchoCampo("//div[@class='bairro-tipo-form']//input[@name='BoletimRg[fechamentos][5][lira]']", '20');
$eu->preenchoCampo("//div[@class='bairro-tipo-form']//input[@name='BoletimRg[fechamentos][5][nao_lira]']", '2');

$eu->clico('Atualizar');
$eu->aguardoPor(1);

$eu->vejoUmTitulo('Boletim de Reconhecimento Geográfico');

$eu->espero('validar fechamento de uma ficha');
$eu->clicoNoGrid('Seminário', 'Ver Fechamento');
$eu->aguardoPor(1);

$eu->vejo('Residencial');
$eu->vejo('Comercial');
$eu->vejo('Terreno Baldio');
$eu->naoVejo('Pontos Estratégicos');
$eu->vejo('Outros');

$eu->clico('Fechar');
$eu->aguardoPor(1);

$eu->espero('atualizar uma ficha');

$eu->clicoNoGrid('123', 'Alterar');
$eu->aguardoPor(1);

$eu->vejoUmTitulo('Atualizar Fechamento de Boletim de RG nº 123');

$eu->preenchoCampo("//div[@class='bairro-tipo-form']//input[@name='BoletimRg[fechamentos][5][lira]']", '0');
$eu->preenchoCampo("//div[@class='bairro-tipo-form']//input[@name='BoletimRg[fechamentos][5][nao_lira]']", '0');

$eu->clico('Atualizar');
$eu->aguardoPor(1);

$eu->espero('validar fechamento de uma ficha');
$eu->clicoNoGrid('Seminário', 'Ver Fechamento');
$eu->aguardoPor(1);

$eu->vejo('Residencial');
$eu->vejo('Comercial');
$eu->vejo('Terreno Baldio');
$eu->naoVejo('Pontos Estratégicos');
$eu->naoVejo('Outros');

$eu->clico('Fechar');
$eu->aguardoPor(1);

$eu->espero('excluir um boletim');
$eu->clicoNoGrid('Seminário', 'Excluir');
$eu->vejoNaPopUp('Confirma a exclusão deste item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->vejo('Nenhum resultado foi encontrado.');
