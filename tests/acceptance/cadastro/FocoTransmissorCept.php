<?php

use \Phactory;

if ($this->scenario->running()) {

    $bairro = Phactory::bairro(['nome' => 'Passo dos Fortes', 'municipio_id' => 1]);

    Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
    Phactory::depositoTipo(['descricao' => 'OVI', 'sigla' => 'OVI', 'municipio_id' => $bairro->municipio_id]);
    Phactory::especieTransmissor(['nome' => 'mosquito', 'municipio_id' => $bairro->municipio_id]);
    Phactory::bairroQuarteirao(['numero_quarteirao' => '418', 'bairro_id' => $bairro->id, 'municipio_id' => $bairro->municipio_id]);
}

$eu = new CaraDaWeb($scenario);
$eu->quero('verificar que o CRUD de focos de transmissores funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Cadastro');
$eu->clico('Focos de Transmissores');

$eu->espero('cadastrar um foco de transmissor');
$eu->clico('Cadastrar Foco de Transmissor');
$eu->aguardoPor(1);
$eu->selecionoOpcao('Tipo de Depósito', 'OVI');
$eu->selecionoOpcao('Espécie de Transmissor', 'mosquito');
$eu->selecionoOpcao('Quarteirão', '418');
$eu->selecionoOpcao('Tipo de Imóvel', 'Residencial');
$eu->preenchoCampo('Endereço', 'Rua Jerusalém 730');
$eu->preenchoCampo('Data da Entrada', '07/03/2014');
$eu->preenchoCampo('Data do Exame', '21/03/2014');
$eu->preenchoCampo('Data da Coleta', '08/04/2014');
$eu->preenchoCampo('Qtde. Forma Aquática', '12');
$eu->preenchoCampo('Qtde. Forma Adulta', '11');
$eu->preenchoCampo('Qtde. Ovos', '10');
$eu->clico('Cadastrar', '.modal');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um foco de transmissor');
$eu->clicoNoGrid('mosquito', 'Alterar');
$eu->aguardoPor(1);
$eu->preenchoCampo('Data da Coleta', '10/04/2014');
$eu->clico('Atualizar', '.modal');
$eu->aguardoPor(1);
$eu->vejo('O registro foi atualizado com sucesso.');
$eu->vejo('10/04/2014');
$eu->naoVejo('08/04/2014');

$eu->espero('excluir um foco de transmissor');
$eu->clicoNoGrid('mosquito', 'Excluir');
$eu->vejoNaPopUp('Tem certeza de que deseja excluir este item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('mosquito', 'tbody');