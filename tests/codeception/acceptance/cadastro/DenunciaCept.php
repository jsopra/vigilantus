<?php
use \Phactory;
use app\models\Cliente;

$eu = new \tests\TesterDeAceitacao($scenario);

$usuario = Phactory::usuario('root', ['login' => 'administrador', 'senha' => 'administrador']);
$modulo = Phactory::modulo(['id' => 1, 'nome' => 'Denuncias']);
$cliente = Cliente::find()->andWhere('id=1')->one();
Phactory::clienteModulo(['cliente_id' => $cliente, 'modulo_id' => $modulo]);
Phactory::bairro(['nome' => 'Seminário', 'cliente_id' => $cliente, 'bairro_categoria_id' => 1]);
Phactory::bairroQuarteirao([
    'numero_quarteirao' => '123',
    'cliente_id' => $cliente,
    'bairro_id' => 1,
]);
Phactory::denunciaTipoProblema(['nome' => 'Problema', 'cliente_id' => $cliente]);

$eu->quero('verificar que o CRUD Denuncias funciona');
$eu->facoLoginComo('administrador', 'administrador');
$eu->clico('Denúncias');

$eu->espero('cadastrar um Denuncia');
$eu->clico('Cadastrar Denúncia');
$eu->aguardoPor(1);
$eu->selecionoOpcao('Bairro', 'Seminário');
$eu->selecionoOpcao('Quarteirão', '123');
$eu->preenchoCampo('Endereço', 'Meu endereço com foco');
$eu->selecionoOpcao('Tipo do Imóvel', 'Casa');
$eu->selecionoOpcao('Tipo do Problema', 'Problema');
$eu->preenchoCampo('Pontos de Referência', 'Bar & Dormitório da Mara');
$eu->preenchoCampo('Mensagem', 'Arresorver eça porra mêu!');
$eu->clico('Cadastrar');
$eu->aguardoPor(1);

$eu->espero('ver detalhes de uma Denuncia');
$eu->clicoNoGrid('Aprovada', 'Ver detalhes');
$eu->vejo('Seminário');

$eu->clico('Denúncias');

$eu->espero('Mudar status de uma denúncia');
$eu->clicoNoGrid('Aprovada', 'Mudar status');
$eu->aguardoPor(1);

$eu->selecionoOpcao('Status', 'Aberto TR');
$eu->vejo('Aberto TR');
