<?php

namespace tests\unit\models;

use Phactory;
use app\models\BoletimRg;
use app\models\BoletimRgFechamento;
use app\models\Imovel;
use app\models\Rua;
use fidelize\phactory\ActiveRecordTest;

class BoletimRgTest extends ActiveRecordTest
{
    public $primeiroBoletimID;

    public function testAdicionarImovel()
    {
        $boletimRg = Phactory::boletimRg();

        $boletimRg->adicionarImovel('Rio de Janeiro', '176', null, 'AP 703', 1, false);

        $arrayEsperado = [
            [
                'rua' => 'Rio de Janeiro',
                'numero' => '176',
                'seq' => null,
                'complemento' => 'AP 703',
                'imovel_tipo' => 1,
                'imovel_lira' => false,
            ]
        ];

        $this->assertEquals($arrayEsperado, $boletimRg->imoveis);
    }

    public function testCRUD()
    {
        $cliente = Phactory::cliente();

        /*
         * boletim 1
         */
        $bairro = Phactory::bairro(['cliente_id' => $cliente->id]);
        $quarteirao = Phactory::bairroQuarteirao(['cliente_id' => $cliente->id]);

        $boletim = new BoletimRg;

        $this->assertFalse($boletim->validate());

        $this->assertArrayHasKey('bairro_id', $boletim->errors);
        $this->assertArrayHasKey('cliente_id', $boletim->errors);
        $this->assertArrayHasKey('bairro_quarteirao_id', $boletim->errors);
        $this->assertArrayHasKey('data', $boletim->errors);

        $boletim->cliente_id = $cliente->id;
        $boletim->inserido_por = 1;
        $boletim->bairro_id = $bairro->id;
        $boletim->bairro_quarteirao_id = $quarteirao->id;
        $boletim->data = date('d/m/Y');
        $boletim->inserido_por = 1;
        $boletim->categoria_id = $bairro->bairro_categoria_id;
        $boletim->imoveis = array(
            $this->criarArrayImovel('Rio de Janeiro', '176', null, 'AP 705', false, 1),
            $this->criarArrayImovel('Rio de Janeiro', '176', null, 'AP 704', false, 1),
            $this->criarArrayImovel('Rio de Janeiro', '176', null, 'AP 703', false, 1),
            $this->criarArrayImovel('Rio de Janeiro', '173', null, null, true, 2),
        );

        $this->assertTrue($boletim->salvarComImoveis());
        $this->assertEquals(4, $boletim->quantidadeImoveis);

        $this->verificarFechamentos([
            // Boletim, tipo imóvel, LIRA?, registros no banco, quantidade
            [$boletim->id, 1, false, 1, 3],
            [$boletim->id, 1, true, 0, null],
            [$boletim->id, 2, false, 1, 1],
            [$boletim->id, 2, true, 1, 1],
        ]);

        $rua = Rua::find()->daRua('Rio de Janeiro')->one();
        $this->assertInstanceOf(Rua::className(), $rua);

        $this->verificarImoveis([
            // $rua, $numero, $complemento, $tipoLira
            [$rua->id, 176, 'AP 705', false],
            [$rua->id, 176, 'AP 704', false],
            [$rua->id, 176, 'AP 703', false],
            [$rua->id, 173, null, true],
        ]);

        $this->primeiroBoletimID = $boletim->id;

        /*
         * boletim 2
         */
        $bairro = Phactory::bairro(['cliente_id' => $cliente->id]);
        $quarteirao = Phactory::bairroQuarteirao(['cliente_id' => $cliente->id]);

        $boletim = new BoletimRg;

        $this->assertFalse($boletim->validate());

        $this->assertArrayHasKey('bairro_id', $boletim->errors);
        $this->assertArrayHasKey('cliente_id', $boletim->errors);
        $this->assertArrayHasKey('bairro_quarteirao_id', $boletim->errors);
        $this->assertArrayHasKey('data', $boletim->errors);

        $boletim->cliente_id = $cliente->id;
        $boletim->inserido_por = 1;
        $boletim->bairro_id = $bairro->id;
        $boletim->bairro_quarteirao_id = $quarteirao->id;
        $boletim->data = date('d/m/Y');
        $boletim->inserido_por = 1;
        $boletim->categoria_id = $bairro->bairro_categoria_id;

        $boletim->imoveis = array(
            $this->criarArrayImovel('Vitorio Cella', '176', null, 'AP 401', false, 1),
            $this->criarArrayImovel('Vitorio Cella', '176', null, 'AP 402', false, 1),
            $this->criarArrayImovel('Vitorio Cella', '176', null, 'AP 403', false, 1),
            $this->criarArrayImovel('Vitorio Cella', '176', null, 'AP 404', false, 1),
            $this->criarArrayImovel('Vitorio Cella', '176', null, 'AP 405', false, 1),
            $this->criarArrayImovel('Vitorio Cella', '178', null, null, true, 3),
            $this->criarArrayImovel('Vitorio Cella', '180', null, null, true, 2),
            $this->criarArrayImovel('Vitorio Cella', '182', null, null, true, 2),
        );

        $this->assertTrue($boletim->salvarComImoveis());
        $this->assertEquals(8, $boletim->quantidadeImoveis);

        $this->verificarFechamentos([
            // Boletim, tipo imóvel, LIRA?, registros no banco, quantidade
            [$boletim->id, 1, false, 1, 5],
            [$boletim->id, 1, true, 0, null],
            [$boletim->id, 2, false, 1, 2],
            [$boletim->id, 2, true, 1, 2],
            [$boletim->id, 3, false, 1, 1],
            [$boletim->id, 3, true, 1, 1],
        ]);

        $rua = Rua::find()->daRua('Vitorio Cella')->one();

        $this->verificarImoveis([
            // $rua, $numero, $complemento, $tipoLira
            [$rua->id, 176, 'AP 401', false],
            [$rua->id, 176, 'AP 402', false],
            [$rua->id, 176, 'AP 403', false],
            [$rua->id, 176, 'AP 404', false],
            [$rua->id, 176, 'AP 405', false],
            [$rua->id, 178, null, true],
            [$rua->id, 180, null, true],
            [$rua->id, 182, null, true],
        ]);

        /*
         * Update model 2
         */
        $boletimUpdate = BoletimRg::findOne($boletim->id);
        $this->assertInstanceOf('app\models\BoletimRg', $boletimUpdate);

        $rua = Rua::find()->daRua('Vitorio Cella')->one();
        $this->assertInstanceOf('app\models\Rua', $rua);

        $this->verificarImoveis([
            // $rua, $numero, $complemento, $tipoLira
            [$rua->id, 176, 'AP 401', false],
        ]);

        $boletimUpdate->imoveis = array(
            $this->criarArrayImovel('Vitorio Cella', '176', null, 'AP 401', true, 1),
            $this->criarArrayImovel('Vitorio Cella', '178', null, null, true, 3),
            $this->criarArrayImovel('Vitorio Cella', '180', null, null, true, 2),
            $this->criarArrayImovel('Vitorio Cella', '182', null, null, true, 2),
        );

        $this->assertTrue($boletimUpdate->salvarComImoveis());

        $this->assertEquals(4, $boletimUpdate->quantidadeImoveis);

        $this->verificarFechamentos([
            // Boletim, tipo imóvel, LIRA?, registros no banco, quantidade
            [$boletimUpdate->id, 1, false, 1, 1],
            [$boletimUpdate->id, 1, true, 1, 1],
            [$boletimUpdate->id, 2, false, 1, 2],
            [$boletimUpdate->id, 2, true, 1, 2],
            [$boletimUpdate->id, 3, false, 1, 1],
            [$boletimUpdate->id, 3, true, 1, 1],
        ]);

        /*
         * Test delete
         */
        //deleta o boletim
        $boletimDelete = BoletimRg::findOne($boletim->id);
        $this->assertInstanceOf('app\models\BoletimRg', $boletimDelete);

        $this->assertEquals(1, $boletimDelete->delete());
        $this->assertEquals(0, BoletimRgFechamento::find()->doBoletim($boletim->id)->count());

        //mantem todas ruas e imoveis
        $rua = Rua::find()->daRua('Vitorio Cella')->one();
        $this->assertInstanceOf(Rua::className(), $rua);

        $this->verificarImoveis([
            // $rua, $numero, $complemento, $tipoLira
            [$rua->id, 176, 'AP 401', true],
            [$rua->id, 176, 'AP 402', false],
            [$rua->id, 176, 'AP 403', false],
            [$rua->id, 176, 'AP 404', false],
            [$rua->id, 176, 'AP 405', false],
            [$rua->id, 178, null, true],
            [$rua->id, 180, null, true],
            [$rua->id, 182, null, true],
        ]);

        //mantem tudo do primeiro boletim
        unset($boletim);
        $boletimPrimeiro = BoletimRg::findOne($this->primeiroBoletimID);
        $this->assertInstanceOf('app\models\BoletimRg', $boletimPrimeiro);

        $this->assertEquals(4, $boletimPrimeiro->quantidadeImoveis);

        $this->verificarFechamentos([
            // Boletim, tipo imóvel, LIRA?, registros no banco, quantidade
            [$boletimPrimeiro->id, 1, false, 1, 3],
            [$boletimPrimeiro->id, 1, true, 0, null],
            [$boletimPrimeiro->id, 2, false, 1, 1],
            [$boletimPrimeiro->id, 2, true, 1, 1],
        ]);

        $rua = Rua::find()->daRua('Rio de Janeiro')->one();
        $this->assertInstanceOf('app\models\Rua', $rua);

        $this->verificarImoveis([
            // $rua, $numero, $complemento, $tipoLira
            [$rua->id, 176, 'AP 705', false],
            [$rua->id, 176, 'AP 704', false],
            [$rua->id, 176, 'AP 703', false],
            [$rua->id, 173, null, true],
        ]);
    }

    public function testCRUDFechamento()
    {
        $cliente = Phactory::cliente();

        $bairro = Phactory::bairro(['cliente_id' => $cliente->id]);
        $quarteirao = Phactory::bairroQuarteirao(['cliente_id' => $cliente->id]);
        $boletimRg = Phactory::boletimRg(['cliente_id' => $cliente->id, 'bairro_id' => $bairro->id]);
        $imovelTipo = Phactory::imovelTipo(['cliente_id' => $cliente->id]);

        //create
        $boletim = new BoletimRgFechamento;

        $this->assertFalse($boletim->validate());

        $this->assertArrayHasKey('boletim_rg_id', $boletim->errors);
        $this->assertArrayHasKey('cliente_id', $boletim->errors);
        $this->assertArrayHasKey('imovel_tipo_id', $boletim->errors);

        $boletim->cliente_id = $cliente->id;
        $boletim->boletim_rg_id = $boletimRg->id;
        $boletim->imovel_tipo_id = $imovelTipo->id;
        $boletim->imovel_lira = false;
        $boletim->quantidade = 10;

        $this->assertTrue($boletim->save());


        $boletimLira = new BoletimRgFechamento;
        $boletimLira->cliente_id = $cliente->id;

        $boletimLira->boletim_rg_id = $boletimRg->id;
        $boletimLira->imovel_tipo_id = $imovelTipo->id;
        $boletimLira->imovel_lira = true;
        $boletimLira->quantidade = 12;

        $this->assertFalse($boletimLira->validate());

        $this->assertArrayHasKey('quantidade', $boletimLira->errors);


        $boletimLira->quantidade = 10;

        $this->assertTrue($boletim->save());

        $this->assertEquals(10, $boletimLira->quantidade);


        //update
        $boletim->quantidade = 15;
        $this->assertTrue($boletim->save());

        $boletimLira->quantidade = 14;
        $this->assertTrue($boletim->save());

        $this->assertEquals(14, $boletimLira->quantidade);


        //delete
        $boletim->delete();

        $boletimLira->refresh();

        $this->assertEquals(14, $boletimLira->quantidade);
    }

    public function testQuantidadeImoveis()
    {
        $cliente = Phactory::cliente();

        $boletimRg = Phactory::boletimRg(['cliente_id' => $cliente->id]);

        $tipoImovel = Phactory::imovelTipo(['cliente_id' => $cliente->id]);

        $quarteirao = Phactory::bairroQuarteirao(['cliente_id' => $cliente->id, 'municipio_id' => $cliente->municipio->id]);

        $rua = Phactory::rua([
            'cliente_id' => $cliente->id,
            'nome' => 'Rua teste',
            'municipio_id' => $cliente->municipio->id
        ]);

        $imovelA = Phactory::imovel([
            'cliente_id' => $cliente->id,
            'municipio_id' => $cliente->municipio->id,
            'rua_id' => $rua->id,
            'bairro_quarteirao_id' => $quarteirao,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => false,
            'numero' => '10',
            'sequencia' => null,
            'complemento' => null
        ]);

        $imovelB = Phactory::imovel([
            'cliente_id' => $cliente->id,
            'municipio_id' => $cliente->municipio->id,
            'rua_id' => $rua->id,
            'bairro_quarteirao_id' => $quarteirao,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => false,
            'numero' => '10',
            'sequencia' => null,
            'complemento' => 'AP 101',
        ]);

        $imovelC = Phactory::imovel([
            'cliente_id' => $cliente->id,
            'municipio_id' => $cliente->municipio->id,
            'rua_id' => $rua->id,
            'bairro_quarteirao_id' => $quarteirao,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => false,
            'numero' => '10',
            'sequencia' => null,
            'complemento' => 'AP 102',
        ]);

        $imovelD = Phactory::imovel([
            'cliente_id' => $cliente->id,
            'municipio_id' => $cliente->municipio->id,
            'rua_id' => $rua->id,
            'bairro_quarteirao_id' => $quarteirao,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => false,
            'numero' => '10',
            'sequencia' => null,
            'complemento' => 'AP 103',
        ]);

        Phactory::boletimRgImovel([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => false,
            'rua_id' => $rua->id,
            'rua_nome' => 'Rua teste',
            'imovel_numero' => '10',
            'imovel_complemento' => null,
            'imovel_id' => $imovelA->id,
        ]);

        Phactory::boletimRgImovel([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => true,
            'rua_id' => $rua->id,
            'rua_nome' => 'Rua teste',
            'imovel_numero' => '10',
            'imovel_complemento' => 'AP 101',
            'imovel_id' => $imovelB->id,
        ]);

        Phactory::boletimRgImovel([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => true,
            'rua_id' => $rua->id,
            'rua_nome' => 'Rua teste',
            'imovel_numero' => '10',
            'imovel_complemento' => 'AP 102',
            'imovel_id' => $imovelC->id,
        ]);

        Phactory::boletimRgImovel([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => true,
            'rua_id' => $rua->id,
            'rua_nome' => 'Rua teste',
            'imovel_numero' => '10',
            'imovel_complemento' => 'AP 103',
            'imovel_id' => $imovelD->id,
        ]);

        $this->assertEquals(4, $boletimRg->getQuantidadeImoveis());
    }

    public function testQuantidadeImoveisFechamento()
    {
        $cliente = Phactory::cliente();

        $boletimRg = Phactory::boletimRg(['cliente_id' => $cliente->id]);

        $tipoImovelA = Phactory::imovelTipo(['cliente_id' => $cliente->id]);
        $tipoImovelB = Phactory::imovelTipo(['cliente_id' => $cliente->id]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovelA->id,
            'imovel_lira' => false,
            'quantidade' => 10
        ]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovelA->id,
            'imovel_lira' => true,
            'quantidade' => 6
        ]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovelB->id,
            'imovel_lira' => false,
            'quantidade' => 12
        ]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovelB->id,
            'imovel_lira' => true,
            'quantidade' => 12
        ]);

        $this->assertEquals((10+12), $boletimRg->getQuantidadeImoveisFechamento());
    }

    public function testPopularImoveis()
    {
        $cliente = Phactory::cliente();

        $boletimRg = Phactory::boletimRg(['cliente_id' => $cliente->id]);

        $tipoImovel = Phactory::imovelTipo(['cliente_id' => $cliente->id]);

        $quarteirao = Phactory::bairroQuarteirao(['cliente_id' => $cliente->id]);

        $rua = Phactory::rua([
            'cliente_id' => $cliente->id,
            'municipio_id' => $cliente->municipio->id,
            'nome' => 'Rua teste',
        ]);

        $imovelA = Phactory::imovel([
            'cliente_id' => $cliente->id,
            'municipio_id' => $cliente->municipio->id,
            'rua_id' => $rua->id,
            'bairro_quarteirao_id' => $quarteirao,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => false,
            'numero' => '10',
            'sequencia' => null,
            'complemento' => null
        ]);

        $imovelB = Phactory::imovel([
            'cliente_id' => $cliente->id,
            'municipio_id' => $cliente->municipio->id,
            'rua_id' => $rua->id,
            'bairro_quarteirao_id' => $quarteirao,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => false,
            'numero' => '10',
            'sequencia' => null,
            'complemento' => 'AP 101',
        ]);

        $imovelC = Phactory::imovel([
            'cliente_id' => $cliente->id,
            'municipio_id' => $cliente->municipio->id,
            'rua_id' => $rua->id,
            'bairro_quarteirao_id' => $quarteirao,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => false,
            'numero' => '10',
            'sequencia' => null,
            'complemento' => 'AP 102',
        ]);

        $imovelD = Phactory::imovel([
            'cliente_id' => $cliente->id,
            'municipio_id' => $cliente->municipio->id,
            'rua_id' => $rua->id,
            'bairro_quarteirao_id' => $quarteirao,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => false,
            'numero' => '10',
            'sequencia' => null,
            'complemento' => 'AP 103',
        ]);

        Phactory::boletimRgImovel([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => false,
            'rua_id' => $rua->id,
            'rua_nome' => 'Rua teste',
            'imovel_numero' => '10',
            'imovel_complemento' => null,
            'imovel_id' => $imovelA->id,
        ]);

        Phactory::boletimRgImovel([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => true,
            'rua_id' => $rua->id,
            'rua_nome' => 'Rua teste',
            'imovel_numero' => '10',
            'imovel_complemento' => 'AP 101',
            'imovel_id' => $imovelB->id,
        ]);

        Phactory::boletimRgImovel([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => true,
            'rua_id' => $rua->id,
            'rua_nome' => 'Rua teste',
            'imovel_numero' => '10',
            'imovel_complemento' => 'AP 102',
            'imovel_id' => $imovelC->id,
        ]);

        Phactory::boletimRgImovel([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => true,
            'rua_id' => $rua->id,
            'rua_nome' => 'Rua teste',
            'imovel_numero' => '10',
            'imovel_complemento' => 'AP 103',
            'imovel_id' => $imovelD->id,
        ]);

        $boletimRg->popularImoveis();

        $this->assertEquals(4, count($boletimRg->imoveis));

        $arrayDados = [
            [
                'rua' => 'Rua teste',
                'numero' => 10,
                'seq' => null,
                'complemento' => null,
                'imovel_tipo' => $tipoImovel->id,
                'imovel_lira' => false,
            ],
            [
                'rua' => 'Rua teste',
                'numero' => 10,
                'seq' => null,
                'complemento' => 'AP 101',
                'imovel_tipo' => $tipoImovel->id,
                'imovel_lira' => true,
            ],
            [
                'rua' => 'Rua teste',
                'numero' => 10,
                'seq' => null,
                'complemento' => 'AP 102',
                'imovel_tipo' => $tipoImovel->id,
                'imovel_lira' => true,
            ],
            [
                'rua' => 'Rua teste',
                'numero' => 10,
                'seq' => null,
                'complemento' => 'AP 103',
                'imovel_tipo' => $tipoImovel->id,
                'imovel_lira' => true,
            ],
        ];

        $this->assertEquals($arrayDados, $boletimRg->imoveis);
    }

    public function testAdicionaImovel()
    {
        $cliente = Phactory::cliente();

        $tipoImovel = Phactory::imovelTipo(['cliente_id' => $cliente->id]);

        $boletimRg = Phactory::boletimRg([
            'cliente_id' => $cliente->id,
        ]);

        $boletimRg->adicionarImovel('Rua teste', 10, null, null, $tipoImovel->id, false);

        $boletimRg->adicionarImovel('Rua teste', 10, null, 'AP 101', $tipoImovel->id, true);

        $boletimRg->adicionarImovel('Rua teste', 10, null, 'AP 102', $tipoImovel->id, true);

        $boletimRg->adicionarImovel('Rua teste', 10, null, 'AP 103', $tipoImovel->id, true);

        $this->assertEquals(4, count($boletimRg->imoveis));

        $arrayDados = [
            [
                'rua' => 'Rua teste',
                'numero' => 10,
                'seq' => null,
                'complemento' => null,
                'imovel_tipo' => $tipoImovel->id,
                'imovel_lira' => false,
            ],
            [
                'rua' => 'Rua teste',
                'numero' => 10,
                'seq' => null,
                'complemento' => 'AP 101',
                'imovel_tipo' => $tipoImovel->id,
                'imovel_lira' => true,
            ],
            [
                'rua' => 'Rua teste',
                'numero' => 10,
                'seq' => null,
                'complemento' => 'AP 102',
                'imovel_tipo' => $tipoImovel->id,
                'imovel_lira' => true,
            ],
            [
                'rua' => 'Rua teste',
                'numero' => 10,
                'seq' => null,
                'complemento' => 'AP 103',
                'imovel_tipo' => $tipoImovel->id,
                'imovel_lira' => true,
            ],
        ];

        $this->assertEquals($arrayDados, $boletimRg->imoveis);
    }

    public function testPopularFechamento()
    {
        $cliente = Phactory::cliente();

        $boletimRg = Phactory::boletimRg(['cliente_id' => $cliente->id]);

        $tipoImovel = Phactory::imovelTipo(['cliente_id' => $cliente->id]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => false,
            'quantidade' => 10
        ]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => true,
            'quantidade' => 6
        ]);

        $boletimRg->popularFechamento();

        $this->assertEquals(1, count($boletimRg->fechamentos));

        $this->assertEquals(2, count($boletimRg->fechamentos[$tipoImovel->id]));

        $arrayDados = [
            $tipoImovel->id => [
                'nao_lira' => 10,
                'lira' => 6,
            ]
        ];

        $this->assertEquals($arrayDados, $boletimRg->fechamentos);
    }

    public function testAdicionarFechamento()
    {
        $cliente = Phactory::cliente();

        $tipoImovel = Phactory::imovelTipo(['cliente_id' => $cliente->id]);

        $boletimRg = Phactory::boletimRg([
            'cliente_id' => $cliente->id,
        ]);

        $boletimRg->adicionarFechamento($tipoImovel->id, false, 10);

        $boletimRg->adicionarFechamento($tipoImovel->id, true, 6);

        $this->assertEquals(1, count($boletimRg->fechamentos));

        $this->assertEquals(2, count($boletimRg->fechamentos[$tipoImovel->id]));

        $arrayDados = [
            $tipoImovel->id => [
                'nao_lira' => 10,
                'lira' => 6,
            ]
        ];

        $this->assertEquals($arrayDados, $boletimRg->fechamentos);
    }

    public function testScopeDaFolha()
    {
        $cliente = Phactory::cliente();

        Phactory::boletimRg([
            'cliente_id' => $cliente->id,
            'folha' => '001',
        ]);

        Phactory::boletimRg([
            'cliente_id' => $cliente->id,
            'folha' => '002',
        ]);

        Phactory::boletimRg([
            'cliente_id' => $cliente->id,
            'folha' => '003',
        ]);

        $this->assertEquals(3, BoletimRg::find()->count());

        $this->assertEquals(1, BoletimRg::find()->daFolha('002')->count());
    }

    public function testScopeDaData()
    {
        $cliente = Phactory::cliente();

        Phactory::boletimRg([
            'cliente_id' => $cliente->id,
            'data' => '09/01/2014',
        ]);

        Phactory::boletimRg([
            'cliente_id' => $cliente->id,
            'data' => '10/01/2014',
        ]);

        Phactory::boletimRg([
            'cliente_id' => $cliente->id,
            'data' => '11/01/2014',
        ]);

        $this->assertEquals(3, BoletimRg::find()->count());

        $this->assertEquals(1, BoletimRg::find()->daData('10/01/2014')->count());
    }

    public function testScopeDoBairro()
    {
        $cliente = Phactory::cliente();

        $bairroA = Phactory::bairro(['cliente_id' => $cliente->id]);
        $bairroB = Phactory::bairro(['cliente_id' => $cliente->id]);

        Phactory::boletimRg([
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairroA->id,
        ]);

        Phactory::boletimRg([
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairroA->id,
        ]);

        Phactory::boletimRg([
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairroB->id,
        ]);

        $this->assertEquals(2, BoletimRg::find()->doBairro($bairroA->id)->count());

        $this->assertEquals(1, BoletimRg::find()->doBairro($bairroB->id)->count());
    }

    public function testScopeDoBairroQuarteirao()
    {
        $cliente = Phactory::cliente();

        $bairro = Phactory::bairro(['cliente_id' => $cliente->id]);

        $bairroQuarteiraoA = Phactory::bairroQuarteirao([
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id
        ]);

        $bairroQuarteiraoB = Phactory::bairroQuarteirao([
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id
        ]);

        Phactory::boletimRg([
            'cliente_id' => $cliente->id,
            'bairro_quarteirao_id' => $bairroQuarteiraoA->id,
        ]);

        Phactory::boletimRg([
            'cliente_id' => $cliente->id,
            'bairro_quarteirao_id' => $bairroQuarteiraoA->id,
        ]);

        Phactory::boletimRg([
            'cliente_id' => $cliente->id,
            'bairro_quarteirao_id' => $bairroQuarteiraoB->id,
        ]);

        $this->assertEquals(2, BoletimRg::find()->doBairroQuarteirao($bairroQuarteiraoA->id)->count());

        $this->assertEquals(1, BoletimRg::find()->doBairroQuarteirao($bairroQuarteiraoB->id)->count());
    }

    /**
     * Cria um array que simula os dados enviados via $_POST no formulário
     * @param string $rua
     * @param string $numero
     * @param string $sequencia
     * @param string $complemento
     * @param boolean $lira
     * @param integer $tipo
     * @return array
     */
    protected function criarArrayImovel($rua, $numero, $sequencia, $complemento, $lira, $tipo)
    {
        return [
            'rua' => $rua,
            'numero' => $numero,
            'seq' => $sequencia,
            'complemento' => $complemento,
            'imovel_lira' => $lira,
            'imovel_tipo' => $tipo,
        ];
    }

    /**
     *
     * @param integer $boletim
     * @param integer $tipoImovel
     * @param boolean $tipoLira
     * @return ActiveQuery
     */
    protected function getBoletimRgFechamentoQuery($boletim, $tipoImovel, $tipoLira)
    {
        return BoletimRgFechamento::find()
                ->doBoletim($boletim)
                ->doTipoDeImovel($tipoImovel)
                ->doTipoLira($tipoLira)
        ;
    }

    /**
     * @param array $fechamentos
     */
    protected function verificarFechamentos(array $fechamentos)
    {
        foreach ($fechamentos as $data) {

            list($boletim, $tipoImovel, $tipoLira, $registros, $quantidade) = $data;

            $query = $this->getBoletimRgFechamentoQuery($boletim, $tipoImovel, $tipoLira);

            $this->assertEquals($registros, $query->count());

            if ($registros) {
                $this->assertEquals($quantidade, $query->one()->quantidade);
            }
        }
    }

    /**
     * @param array $imoveis
     */
    protected function verificarImoveis(array $imoveis)
    {
        foreach ($imoveis as $data) {

            list($rua, $numero, $complemento, $tipoLira) = $data;

            $imovel = $this->getImovel($rua, $numero, $complemento, $tipoLira);

            $this->assertInstanceOf(Imovel::className(), $imovel);
        }
    }

    /**
     * @param string $rua
     * @param integer $numero
     * @param string $complemento
     * @param boolean $tipoLira
     * @return Imovel
     */
    protected function getImovel($rua, $numero, $complemento, $tipoLira)
    {
        $query = Imovel::find()
            ->daRua($rua)
            ->doNumero($numero)
            ->doTipoLira($tipoLira)
        ;

        if ($complemento) {
            $query->doComplemento($complemento);
        }

        return $query->one();
    }
}
