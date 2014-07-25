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
$eu->clico('Preencher novo Boletim');
$eu->vejoUmTitulo('Preencher Boletim de RG');
$eu->selecionoOpcao('Bairro', 'Seminário');
$eu->aguardoPor(1);
$eu->vejo('Urbano');
$eu->selecionoOpcao('Quarteirão', '123');
$eu->preenchoCampo('Folha nº', '123');
$eu->preenchoCampo('Data da Coleta', date('d/m/Y'));

$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('Nenhum imóvel salvo');

$eu->preenchoCampo("//input[@name='BoletimRg[imoveis][exemplo][rua]']", 'Rua Rio de Janeiro');
$eu->preenchoCampo("//input[@name='BoletimRg[imoveis][exemplo][numero]']", '176');
$eu->preenchoCampo("//input[@name='BoletimRg[imoveis][exemplo][complemento]']", 'AP 705');
$eu->selecionoOpcao("//select[@name='BoletimRg[imoveis][exemplo][imovel_tipo]']", 'Residencial');
$eu->desmarcoOpcao("//input[@name='BoletimRg[imoveis][exemplo][imovel_lira]']");
$eu->clico("//a[@title='Adicionar']");

$eu->aguardoPor(1);

$eu->preenchoCampo("//input[@name='BoletimRg[imoveis][exemplo][rua]']", 'Rua Rio de Janeiro');
$eu->preenchoCampo("//input[@name='BoletimRg[imoveis][exemplo][numero]']", '176');
$eu->preenchoCampo("//input[@name='BoletimRg[imoveis][exemplo][complemento]']", 'AP 704');
$eu->selecionoOpcao("//select[@name='BoletimRg[imoveis][exemplo][imovel_tipo]']", 'Residencial');
$eu->desmarcoOpcao("//input[@name='BoletimRg[imoveis][exemplo][imovel_lira]']");
$eu->clico("//a[@title='Adicionar']");

$eu->aguardoPor(1);

$eu->preenchoCampo("//input[@name='BoletimRg[imoveis][exemplo][rua]']", 'Rua Rio de Janeiro');
$eu->preenchoCampo("//input[@name='BoletimRg[imoveis][exemplo][numero]']", '176');
$eu->preenchoCampo("//input[@name='BoletimRg[imoveis][exemplo][complemento]']", 'SL 03');
$eu->selecionoOpcao("//select[@name='BoletimRg[imoveis][exemplo][imovel_tipo]']", 'Comercial');
$eu->marcoOpcao("//input[@name='BoletimRg[imoveis][exemplo][imovel_lira]']");
$eu->clico("//a[@title='Adicionar']");

$eu->aguardoPor(1);

$eu->clico('Atualizar');
$eu->aguardoPor(1);

$eu->vejoUmTitulo('Boletim de Reconhecimento Geográfico');

$eu->espero('validar fechamento de uma ficha');
$eu->clicoNoGrid('Seminário', 'Ver Fechamento');
$eu->aguardoPor(1);

$eu->vejo('Residencial');
$eu->vejo('Comercial');

$eu->clico('Fechar');
$eu->aguardoPor(1);

$eu->espero('atualizar uma ficha');

$eu->clicoNoGrid('123', 'Alterar');
$eu->aguardoPor(1);

$eu->vejoUmTitulo('Atualizar Boletim de RG nº 123');

$eu->clico("//a[@title='Remover 2']");

$eu->clico('Atualizar');
$eu->aguardoPor(1);

$eu->espero('validar fechamento de uma ficha');
$eu->clicoNoGrid('Seminário', 'Ver Fechamento');
$eu->aguardoPor(1);

$eu->vejo('Residencial');
$eu->naoVejo('Comercial');

$eu->clico('Fechar');
$eu->aguardoPor(1);

$eu->espero('excluir um boletim');
$eu->clicoNoGrid('Seminário', 'Excluir');
$eu->vejoNaPopUp('Confirma a exclusão deste item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->vejo('Nenhum resultado foi encontrado.');
