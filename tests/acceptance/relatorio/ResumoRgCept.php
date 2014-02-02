<?php

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o relatório boletim resumo de reconhecimento geográfico funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Relatórios');
$eu->clico('Resumo de RG');
$eu->selecionoOpcao('Mês/Ano', '10/2013');
$eu->selecionoOpcao('Bairro', 'Passo dos Fortes');
$eu->naoVejo('Exportar planilha');
$eu->clico('Gerar Planilha');

$eu->espero('ver o cabeçalho da planilha');
$seletorCabecalho = '#boletim-resumo-rg thead';
$eu->vejo('Número Principal', $seletorCabecalho);
$eu->vejo('Número Alternativo', $seletorCabecalho);
$eu->vejo('Quarteirão', $seletorCabecalho);
$eu->vejo('Tipo do Imóvel', $seletorCabecalho);
$eu->vejo('Seq.', $seletorCabecalho);
$eu->vejo('Res.', $seletorCabecalho);
$eu->vejo('Comercial', $seletorCabecalho);
$eu->vejo('TB', $seletorCabecalho);
$eu->vejo('PE', $seletorCabecalho);
$eu->vejo('Outros', $seletorCabecalho);
$eu->vejo('Total de Imóveis', $seletorCabecalho);
$eu->vejo('Área de Foco', $seletorCabecalho);

$eu->espero('ver opção de exportar planilha do Excel');
$eu->vejo('Exportar planilha');

$eu->espero('ver os dados na planilha');
$eu->vejo(implode(' ', ['14', '973', '3', '5', '1', '0', '0', '9', '27/06/2013']));
$eu->vejo(implode(' ', ['171', '2629', '1', '170', '2', '2', '0', '0', '174', '02/04/2013']));
