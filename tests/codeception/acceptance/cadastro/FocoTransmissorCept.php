<?php

use \Phactory;
use app\models\Cliente;

$eu = new \tests\TesterDeAceitacao($scenario);

$cliente = Cliente::find()->andWhere('id=1')->one();

$bairro = Phactory::bairro(['nome' => 'Passo dos Fortes', 'cliente_id' => $cliente, 'bairro_categoria_id' => 1]);

Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
Phactory::depositoTipo(['descricao' => 'OVI', 'sigla' => 'OVI', 'cliente_id' => $cliente,]);
Phactory::especieTransmissor(['nome' => 'mosquito', 'cliente_id' => $cliente,]);
$rua = Phactory::rua(['cliente_id' => $cliente]);
$quarteirao = Phactory::bairroQuarteirao([
    'numero_quarteirao' => '418',
    'bairro_id' => $bairro->id,
    'cliente_id' => $cliente,
]);
$imovel = Phactory::imovel([
    'rua_id' => $rua,
    'bairro_quarteirao_id' => $quarteirao->id,
    'cliente_id' => $cliente,
]);

$eu->quero('verificar que o CRUD de focos de transmissores funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clicoNoMenu(['Focos']);

$eu->espero('cadastrar um foco de transmissor');
$eu->clico('Cadastrar Foco de Transmissor');
$eu->aguardoPor(1);
$eu->preenchoCampo('Laboratório', 'lab1');
$eu->preenchoCampo('Técnico', 'tecn1');
$eu->markSelect2Option('Tipo de Depósito', 'OVI');
$eu->markSelect2Option('Espécie de Transmissor', 'mosquito');

$eu->selecionoOpcao('Bairro', 'Passo dos Fortes');
$eu->aguardoPor(1);
$eu->vejo('Urbano');
$eu->selecionoOpcao('Quarteirão', '418');

$eu->markAjaxSelect2Option('Endereço do Imóvel', 'Rua 0001, S/N, Bairro Passo dos Fortes');
$eu->preenchoCampo('Data da Entrada', '07/03/2014');
$eu->preenchoCampo('Data do Exame', '21/03/2014');
$eu->preenchoCampo('Data da Coleta', '08/04/2014');
$eu->preenchoCampo('Qtde. Forma Aquática', '12');
$eu->preenchoCampo('Qtde. Forma Adulta', '11');
$eu->preenchoCampo('Qtde. Ovos', '10');
$eu->clico('Cadastrar');
$eu->aguardoPor(1);
$eu->vejo('O cadastro foi realizado com sucesso.');

$eu->espero('editar um foco de transmissor');
$eu->clicoNoGrid('mosquito', 'Alterar');
$eu->aguardoPor(1);
$eu->preenchoCampo('Data da Coleta', '10/04/2014');
$eu->clico('Atualizar');
$eu->aguardoPor(1);

$eu->vejo('10/04/2014');
$eu->naoVejo('08/04/2014');

$eu->espero('excluir um foco de transmissor');
$eu->clicoNoGrid('mosquito', 'Excluir');
$eu->vejoNaPopUp('Confirma a exclusão deste item?');
$eu->aceitoPopUp();
$eu->aguardoPor(1);
$eu->naoVejo('mosquito', 'tbody');
