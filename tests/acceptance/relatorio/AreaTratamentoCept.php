<?php

use app\models\ImovelTipo;
use app\models\Municipio;
use \Phactory;

if ($this->scenario->running()) {

    $usuario = Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
    
    $bairro = Phactory::bairro(['nome' => 'Seminário', 'municipio_id' => 1]);
    
    $rua = Phactory::rua(['municipio_id' => 1, 'nome' => 'Rua 0001']);
    
    $tipoDeposito = Phactory::depositoTipo(['municipio_id' => 1, 'descricao' => 'TD0001', 'sigla' => 'TD0001']);
    
    $especieTransmissor = Phactory::especieTransmissor(['municipio_id' => 1, 'nome' => 'Aedes_0001']);
    
    $especieTransmissor2 = Phactory::especieTransmissor(['municipio_id' => 1, 'nome' => 'Aedes_0002']);
        
    $quarteiraoA = Phactory::bairroQuarteirao([
        'municipio_id' => 1,
        'numero_quarteirao' => 1,
        'bairro_id' => $bairro->id,
        'coordenadasJson' => '[{"A":"-27.084606946049","k":"-52.610427439213"},{"A":"-27.085101274411","k":"-52.610169947147"},{"A":"-27.084843364233","k":"-52.608764469624"},{"A":"-27.08433709438","k":"-52.608957588673"},{"A":"-27.084556796673","k":"-52.61054545641"},{"A":"-27.084606946049","k":"-52.610427439213"}]',
        'coordenadas_area' => '0103000020E610000001000000060000007E4B02CDA8153BC01C00807C224E4AC073717632C9153BC0CCFF7F0C1A4E4AC0D1A9724BB8153BC03D0080FEEB4D4AC0A733A61D97153BC033008052F24D4AC0745DA483A5153BC04D00805A264E4AC07E4B02CDA8153BC01C00807C224E4AC0',
    ]);

    $quarteiraoB = Phactory::bairroQuarteirao([
        'municipio_id' => 1,
        'numero_quarteirao' => 2,  
        'bairro_id' => $bairro->id,
        'coordenadasJson' => '[{"A":"-27.084977095511","k":"-52.611755132675"},{"A":"-27.085550227749","k":"-52.61160492897"},{"A":"-27.085110826628","k":"-52.610210180283"},{"A":"-27.084614110243","k":"-52.610446214676"},{"A":"-27.084977095511","k":"-52.611755132675"}]',
        'coordenadas_area' => '0103000020E61000000100000005000000FA20160FC1153BC0E8FFFFFD4D4E4AC0E958A69EE6153BC0D1FFFF11494E4AC01DE7B8D2C9153BC03900005E1B4E4AC019463445A9153BC00E00001A234E4AC0FA20160FC1153BC0E8FFFFFD4D4E4AC0',
    ]);

    $quarteiraoC = Phactory::bairroQuarteirao([
        'municipio_id' => 1,
        'numero_quarteirao' => 3,
        'bairro_id' => $bairro->id,
        'coordenadasJson' => '[{"A":"-27.082856480737","k":"-52.613750696182"},{"A":"-27.083945450094","k":"-52.613471746445"},{"A":"-27.083677984917","k":"-52.612291574478"},{"A":"-27.082589012961","k":"-52.612570524216"},{"A":"-27.082856480737","k":"-52.613750696182"}]',
        'coordenadas_area' => '0103000020E61000000100000005000000EE06131536153BC0DDFFFF618F4E4AC03ECDF2727D153BC02A00003E864E4AC01B9DA0EB6B153BC0EBFFFF915F4E4AC02AADB58D24153BC02A0000B6684E4AC0EE06131536153BC0DDFFFF618F4E4AC0',
    ]);

    $quarteiraoD = Phactory::bairroQuarteirao([
        'municipio_id' => 1,
        'numero_quarteirao' => 4,
        'bairro_id' => $bairro->id,
        'coordenadasJson' => '[{"A":"-27.08745109535","k":"-52.61846601963"},{"A":"-27.087260054838","k":"-52.618723511696"},{"A":"-27.085712614683","k":"-52.616663575172"},{"A":"-27.085091722193","k":"-52.61628806591"},{"A":"-27.084614110243","k":"-52.61618077755"},{"A":"-27.083639775554","k":"-52.615730166435"},{"A":"-27.083467833258","k":"-52.615622878075"},{"A":"-27.083515595033","k":"-52.614786028862"},{"A":"-27.086252310745","k":"-52.614906728268"},{"A":"-27.086338279789","k":"-52.61604398489"},{"A":"-27.086643946967","k":"-52.617503106594"},{"A":"-27.086739467789","k":"-52.617824971676"},{"A":"-27.086920957126","k":"-52.617969810963"},{"A":"-27.08745109535","k":"-52.61846601963"}]',
        'coordenadas_area' => '0103000020E6100000010000000E0000001387EA3163163BC0C3FFFFE4294F4AC0C1C6C9AC56163BC013000055324F4AC00BEB0C43F1153BC0C4FFFFD4EE4E4AC0B0FA3392C8153BC0D0FFFF86E24E4AC019463445A9153BC024000003DF4E4AC033A6946A69153BC0DEFFFF3ED04E4AC0701CDE255E153BC0320000BBCC4E4AC06A5F2D4761153BC00000004FB14E4AC0ADDAA5A114163BC02E008043B54E4AC02F16F8431A163BC002008087DA4E4AC0A09E364C2E163BC0F4FF7F570A4F4AC01B6DC98E34163BC0120080E3144F4AC07531AC7340163BC02D0080A2194F4AC01387EA3163163BC0C3FFFFE4294F4AC0',
    ]);

    $imovel = Phactory::imovel([
        'municipio_id' => 1,
        'bairro_quarteirao_id' => $quarteiraoC->id,
        'rua_id' => $rua->id,
    ]);

    $foco = Phactory::focoTransmissor([
        'imovel_id' => $imovel->id,
        'tipo_deposito_id' => $tipoDeposito->id,
        'especie_transmissor_id' => $especieTransmissor->id,
    ]);
}

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o relatório de áreas de tratamento funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Relatórios');
$eu->clico('Áreas de Tratamento', 'li.active');

$eu->aguardoPor(1);

//sem filtro
$eu->espero('ver os dados de area de tratamento sem filtro');
$eu->clico('a[href="#w3-tab1"]');
$eu->vejo(implode(' ', ['Seminário', '1', '(não definido)']));
$eu->vejo(implode(' ', ['Seminário', '2', '(não definido)']));
$eu->vejo(implode(' ', ['Seminário', '3', '(não definido)']));
$eu->vejo(implode(' ', ['Seminário', '4', '(não definido)']));

$eu->espero('ver os dados de focos sem filtro');
$eu->clico('a[href="#w3-tab2"]');
$eu->vejo(implode(' ', ['Seminário', '3', 'Rua 0001, S/N', 'TD0001', 'Aedes_0001']));
$eu->naoVejo(implode(' ', ['Seminário', '4', 'Rua 0001, S/N', 'TD0001', 'Aedes_0001']));


//filtro de bairro
$eu->espero('fazer teste de relatório com aplicação de filtro de bairro');
$eu->clico('Relatórios');
$eu->clico('Áreas de Tratamento', 'li.active');

$eu->aguardoPor(1);
$eu->selecionoOpcao('Bairro', 'Seminário');
$eu->clico('Gerar');
$eu->aguardoPor(1);

$eu->espero('ver os dados de area de tratamento com filtro de bairro');
$eu->clico('a[href="#w3-tab1"]');
$eu->vejo(implode(' ', ['Seminário', '1', '(não definido)']));
$eu->vejo(implode(' ', ['Seminário', '2', '(não definido)']));
$eu->vejo(implode(' ', ['Seminário', '3', '(não definido)']));
$eu->vejo(implode(' ', ['Seminário', '4', '(não definido)']));

$eu->espero('ver os dados de focos com filtro de bairro');
$eu->clico('a[href="#w3-tab2"]');
$eu->vejo(implode(' ', ['Seminário', '3', 'Rua 0001, S/N', 'TD0001', 'Aedes_0001']));
$eu->naoVejo(implode(' ', ['Seminário', '4', 'Rua 0001, S/N', 'TD0001', 'Aedes_0001']));


//filtor lira nao
$eu->espero('fazer teste de relatório com aplicação de filtro de lira não');
$eu->clico('Relatórios');
$eu->clico('Áreas de Tratamento', 'li.active');

$eu->aguardoPor(1);
$eu->selecionoOpcao('LIRA', 'Não');
$eu->clico('Gerar');
$eu->aguardoPor(1);

$eu->espero('ver os dados de area de tratamento com filtro de lira não');
$eu->clico('a[href="#w3-tab1"]');
$eu->vejo(implode(' ', ['Seminário', '1', '(não definido)']));
$eu->vejo(implode(' ', ['Seminário', '2', '(não definido)']));
$eu->vejo(implode(' ', ['Seminário', '3', '(não definido)']));
$eu->vejo(implode(' ', ['Seminário', '4', '(não definido)']));

$eu->espero('ver os dados de focos com filtro de lira não');
$eu->clico('a[href="#w3-tab2"]');
$eu->vejo(implode(' ', ['Seminário', '3', 'Rua 0001, S/N', 'TD0001', 'Aedes_0001']));
$eu->naoVejo(implode(' ', ['Seminário', '4', 'Rua 0001, S/N', 'TD0001', 'Aedes_0001']));


//filtro lira sim
$eu->espero('ver os dados de area de tratamento com filtro de lira sim');
$eu->clico('Relatórios');
$eu->clico('Áreas de Tratamento', 'li.active');
$eu->aguardoPor(1);
$eu->selecionoOpcao('LIRA', 'Sim');
$eu->clico('Gerar');
$eu->aguardoPor(1);
$eu->clico('a[href="#w3-tab1"]');
$eu->naoVejo(implode(' ', ['Seminário', '1', '(não definido)']));
$eu->naoVejo(implode(' ', ['Seminário', '2', '(não definido)']));
$eu->naoVejo(implode(' ', ['Seminário', '3', '(não definido)']));
$eu->naoVejo(implode(' ', ['Seminário', '4', '(não definido)']));

$eu->espero('ver os dados de focos com filtro de lira sim');
$eu->clico('a[href="#w3-tab2"]');
$eu->naoVejo(implode(' ', ['Seminário', '3', 'Rua 0001, S/N', 'TD0001', 'Aedes_0001']));


//filtro Aedes_0002
$eu->espero('ver os dados de area de tratamento com filtro de aedes_0001');
$eu->clico('Relatórios');
$eu->clico('Áreas de Tratamento', 'li.active');
$eu->aguardoPor(1);
$eu->selecionoOpcao('Espécie de Transmissor', 'Aedes_0001');
$eu->clico('Gerar');
$eu->aguardoPor(1);
$eu->clico('a[href="#w3-tab2"]');
$eu->vejo(implode(' ', ['Seminário', '3', 'Rua 0001, S/N', 'TD0001', 'Aedes_0001']));

//filtro Aedes_0002
$eu->espero('ver os dados de area de tratamento com filtro de aedes_0002');
$eu->clico('Relatórios');
$eu->clico('Áreas de Tratamento', 'li.active');
$eu->aguardoPor(1);
$eu->selecionoOpcao('Espécie de Transmissor', 'Aedes_0002');
$eu->clico('Gerar');
$eu->aguardoPor(1);
$eu->clico('a[href="#w3-tab2"]');
$eu->naoVejo(implode(' ', ['Seminário', '3', 'Rua 0001, S/N', 'TD0001', 'Aedes_0001']));

//detalhamento de foco da area de tratamento
$eu->espero('fazer teste de relatório com aplicação de filtro de bairro + lira não');
$eu->clico('Relatórios');
$eu->clico('Áreas de Tratamento', 'li.active');

$eu->aguardoPor(1);
$eu->selecionoOpcao('Bairro', 'Seminário');
$eu->selecionoOpcao('LIRA', 'Não');
$eu->clico('Gerar');
$eu->aguardoPor(1);

$eu->espero('ver os dados de area de tratamento com filtro de lira nao');
$eu->clico('a[href="#w3-tab1"]');
$eu->vejo(implode(' ', ['Seminário', '1', '(não definido)']));
$eu->vejo(implode(' ', ['Seminário', '2', '(não definido)']));
$eu->vejo(implode(' ', ['Seminário', '3', '(não definido)']));
$eu->vejo(implode(' ', ['Seminário', '4', '(não definido)']));

$eu->espero('ver os dados de focos com filtro de lira não');
$eu->clico('a[href="#w3-tab2"]');
$eu->vejo(implode(' ', ['Seminário', '3', 'Rua 0001, S/N', 'TD0001', 'Aedes_0001']));

$eu->espero('detalhar focos de uma área de tratamento');
$eu->clico('a[href="#w3-tab1"]');
$eu->clicoNoGrid('1', 'Ver Focos');
$eu->aguardoPor(1);
$eu->vejo(implode(' ', ['Seminário', '3', 'Rua 0001, S/N', 'TD0001', 'Aedes_0001']));