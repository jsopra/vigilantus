<?php
use \Phactory;
use app\models\Cliente;

$eu = new \tests\TesterDeAceitacao($scenario);

$usuario = Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
$modulo = Phactory::modulo(['id' => 1, 'nome' => 'Denuncias']);
$cliente = Cliente::find()->andWhere('id=1')->one();
$cliente->rotulo = 'chapeco';
$cliente->save();
Phactory::clienteModulo(['cliente_id' => $cliente, 'modulo_id' => $modulo]);
Phactory::bairro(['nome' => 'Seminário', 'cliente_id' => $cliente, 'bairro_categoria_id' => 1]);
Phactory::bairroQuarteirao([
    'numero_quarteirao' => '123',
    'cliente_id' => $cliente,
    'bairro_id' => 1,
]);
Phactory::ocorrenciaTipoProblema(['nome' => 'Problema', 'cliente_id' => $cliente]);

$eu->quero('verificar que o envio de denúncia funciona');
$eu->estouNaPagina(Yii::$app->homeUrl . '?r=cidade/index&id=' . $cliente->id);

$eu->vejo('Chapecó/SC');

$eu->vejo('Denuncie!');

$eu->selecionoOpcao('Bairro', 'Seminário');
$eu->preenchoCampo('Endereço', 'Meu endereço com foco');
$eu->selecionoOpcao('Tipo do Imóvel', 'Casa');
$eu->selecionoOpcao('Tipo do Problema', 'Problema');
$eu->preenchoCampo('Pontos de Referência', 'Bar & Dormitório da Mara');
$eu->preenchoCampo('Mensagem', 'Arresorver eça porra mêu!');
$eu->clico('Enviar');
$eu->aguardoPor(1);

$eu->vejo('Denúncia realizada com sucesso. Você será notificado quando a denúncia for avaliada.');

$eu->quero('verificar que denúncia está no crud');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Denúncias');

$eu->espero('ver detalhes de uma Denuncia');
$eu->clicoNoGrid('Em Avaliação', 'Ver detalhes');
$eu->vejo('Seminário');
