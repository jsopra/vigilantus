<?php

namespace tests\unit\models;

use Yii;
use Phactory;
use perspectiva\phactory\ActiveRecordTest;
use app\models\FocoTransmissor;
use app\models\Configuracao;
use app\models\Cliente;
use app\models\ConfiguracaoCliente;

class FocoTransmissorTest extends ActiveRecordTest
{

    public function testIsAtivo()
    {
        $cliente = Cliente::find(['id' => 1])->one();

        $foco = Phactory::focoTransmissor(['cliente' => $cliente]);
        $this->assertTrue($foco->isAtivo());

        $foco = Phactory::focoTransmissor([
            'cliente' => $cliente,
            'data_coleta' => date("Y-m-d", strtotime("-360 days"))
        ]);
        $this->assertTrue($foco->isAtivo());

        $foco = Phactory::focoTransmissor([
            'cliente' => $cliente,
            'data_coleta' => date("Y-m-d", strtotime("-361 days"))
        ]);
        $this->assertFalse($foco->isAtivo());
    }

    public function testIsInformacaoPublica()
    {
        $foco = Phactory::focoTransmissor([]);
        $this->assertTrue($foco->isInformacaoPublica());

        $foco = Phactory::focoTransmissor([
            'data_coleta' => date("Y-m-d", strtotime("-59 days"))
        ]);
        $this->assertTrue($foco->isInformacaoPublica());

        $foco = Phactory::focoTransmissor([
            'data_coleta' => date("Y-m-d", strtotime("-61 days"))
        ]);
        $this->assertFalse($foco->isInformacaoPublica());
    }

    public function testIsInformacaoPublicaDoCliente()
    {
        $this->assertEquals(1, Configuracao::find()->count());

        $cliente = Phactory::cliente();

        $configuracao = Configuracao::find()->doId(Configuracao::ID_QUANTIDADE_DIAS_INFORMACAO_PUBLICA)->one();

        $this->assertInstanceOf('\app\models\Configuracao', $configuracao);

        $configuracaoCliente = ConfiguracaoCliente::find()->doCliente($cliente->id)->doIdConfiguracao($configuracao->id)->one();

        $this->assertInstanceOf('\app\models\ConfiguracaoCliente', $configuracaoCliente);

        $configuracaoCliente->valor = '100';

        $this->assertTrue($configuracaoCliente->save());

        $foco = Phactory::focoTransmissor(['cliente' => $cliente]);
        $this->assertTrue($foco->isInformacaoPublica());

        $foco = Phactory::focoTransmissor([
            'data_coleta' => date("Y-m-d", strtotime("-59 days")),
            'cliente' => $cliente,
        ]);
        $this->assertTrue($foco->isInformacaoPublica());

        $foco = Phactory::focoTransmissor([
            'data_coleta' => date("Y-m-d", strtotime("-61 days")),
            'cliente' => $cliente,
        ]);
        $this->assertTrue($foco->isInformacaoPublica());

        $foco = Phactory::focoTransmissor([
            'data_coleta' => date("Y-m-d", strtotime("-101 days")),
            'cliente' => $cliente,
        ]);
        $this->assertFalse($foco->isInformacaoPublica());
    }

    public function testAreaDeTratamento() {

        //cenário
        $quarteiraoA = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'coordenadasJson' => '[{"A":"-27.084606946049","k":"-52.610427439213"},{"A":"-27.085101274411","k":"-52.610169947147"},{"A":"-27.084843364233","k":"-52.608764469624"},{"A":"-27.08433709438","k":"-52.608957588673"},{"A":"-27.084556796673","k":"-52.61054545641"},{"A":"-27.084606946049","k":"-52.610427439213"}]',
            'coordenadas_area' => '0103000020E610000001000000060000007E4B02CDA8153BC01C00807C224E4AC073717632C9153BC0CCFF7F0C1A4E4AC0D1A9724BB8153BC03D0080FEEB4D4AC0A733A61D97153BC033008052F24D4AC0745DA483A5153BC04D00805A264E4AC07E4B02CDA8153BC01C00807C224E4AC0',
        ]);

        $quarteiraoB = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'coordenadasJson' => '[{"A":"-27.084977095511","k":"-52.611755132675"},{"A":"-27.085550227749","k":"-52.61160492897"},{"A":"-27.085110826628","k":"-52.610210180283"},{"A":"-27.084614110243","k":"-52.610446214676"},{"A":"-27.084977095511","k":"-52.611755132675"}]',
            'coordenadas_area' => '0103000020E61000000100000005000000FA20160FC1153BC0E8FFFFFD4D4E4AC0E958A69EE6153BC0D1FFFF11494E4AC01DE7B8D2C9153BC03900005E1B4E4AC019463445A9153BC00E00001A234E4AC0FA20160FC1153BC0E8FFFFFD4D4E4AC0',
        ]);

        $quarteiraoC = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'coordenadasJson' => '[{"A":"-27.082856480737","k":"-52.613750696182"},{"A":"-27.083945450094","k":"-52.613471746445"},{"A":"-27.083677984917","k":"-52.612291574478"},{"A":"-27.082589012961","k":"-52.612570524216"},{"A":"-27.082856480737","k":"-52.613750696182"}]',
            'coordenadas_area' => '0103000020E61000000100000005000000EE06131536153BC0DDFFFF618F4E4AC03ECDF2727D153BC02A00003E864E4AC01B9DA0EB6B153BC0EBFFFF915F4E4AC02AADB58D24153BC02A0000B6684E4AC0EE06131536153BC0DDFFFF618F4E4AC0',
        ]);

        $foco = Phactory::focoTransmissor([
            'bairro_quarteirao_id' => $quarteiraoA->id
        ]);

        //testes
        $this->assertEquals(0, FocoTransmissor::find()->daAreaDeTratamento($quarteiraoC)->count());
        $this->assertEquals(1, FocoTransmissor::find()->daAreaDeTratamento($quarteiraoA)->count()); //quarteirão do foco é área de tratamento dele mesmo!
        $this->assertEquals(1, FocoTransmissor::find()->daAreaDeTratamento($quarteiraoB)->count());
    }

    public function testDaEntrada()
    {
        $foco = Phactory::focoTransmissor([]);

        $foco = Phactory::focoTransmissor([
            'data_entrada' => date("Y-m-d", strtotime("-10 days"))
        ]);

        $foco = Phactory::focoTransmissor([
            'data_entrada' => date("Y-m-d", strtotime("-15 days"))
        ]);

        $this->assertEquals(3, FocoTransmissor::find()->count());
        $this->assertEquals(3, FocoTransmissor::find()->dataEntradaEntre(date("Y-m-d", strtotime("-15 days")), date("Y-m-d"))->count());
        $this->assertEquals(2, FocoTransmissor::find()->dataEntradaEntre(date("Y-m-d", strtotime("-10 days")), date("Y-m-d"))->count());
        $this->assertEquals(1, FocoTransmissor::find()->dataEntradaEntre(date("Y-m-d", strtotime("-5 days")), date("Y-m-d"))->count());
    }

    public function testScopePorMes()
    {
        Phactory::focoTransmissor();
        Phactory::focoTransmissor();
        Phactory::focoTransmissor();

        $record = FocoTransmissor::find()->doAno(date('Y'))->porMes()->all();

        $this->assertTrue(is_array($record));

        $this->assertEquals(1, count($record));

        $this->assertEquals(date('m'), $record[0]->mes);

        $this->assertEquals(3, $record[0]->quantidade_registros);

        if(date('m') == 12) {
            $this->assertFalse(isset($record[date('m') - 1]));
        }
        else {
            $this->assertFalse(isset($record[date('m') + 1]));
        }
    }

    public function testScopeDoMes()
    {
        $foco = Phactory::focoTransmissor([
            'data_entrada' => '2014-10-01'
        ]);

        $foco = Phactory::focoTransmissor([
            'data_entrada' => '2014-10-01'
        ]);

        $foco = Phactory::focoTransmissor([
            'data_entrada' => '2014-09-01'
        ]);

        $this->assertEquals(2, FocoTransmissor::find()->doMes(10)->count());
        $this->assertEquals(1, FocoTransmissor::find()->doMes(9)->count());
    }

    public function testScopeDoAno()
    {
        $foco = Phactory::focoTransmissor([
            'data_entrada' => '2014-10-01'
        ]);

        $foco = Phactory::focoTransmissor([
            'data_entrada' => '2013-10-01'
        ]);

        $foco = Phactory::focoTransmissor([
            'data_entrada' => '2014-09-01'
        ]);

        $this->assertEquals(2, FocoTransmissor::find()->doAno(2014)->count());
        $this->assertEquals(1, FocoTransmissor::find()->doAno(2013)->count());
    }

    public function testScopeComQuantidadeEm()
    {
        $foco = Phactory::focoTransmissor([
            'quantidade_ovos' => 5,
            'quantidade_forma_adulta' => 0,
            'quantidade_forma_aquatica' => 0
        ]);

        $foco = Phactory::focoTransmissor([
            'quantidade_ovos' => 5,
            'quantidade_forma_adulta' => 0,
            'quantidade_forma_aquatica' => 5
        ]);

        $foco = Phactory::focoTransmissor([
            'quantidade_ovos' => 5,
            'quantidade_forma_adulta' => 4,
            'quantidade_forma_aquatica' => 0
        ]);

        $this->assertEquals(3, FocoTransmissor::find()->comQuantidadeEm('quantidade_ovos')->count());
        $this->assertEquals(1, FocoTransmissor::find()->comQuantidadeEm('quantidade_forma_adulta')->count());
        $this->assertEquals(1, FocoTransmissor::find()->comQuantidadeEm('quantidade_forma_aquatica')->count());
    }

    public function testScopeDoTipoDeposito()
    {
        $tipoDepositoA = Phactory::depositoTipo();
        $tipoDepositoB = Phactory::depositoTipo();

        Phactory::focoTransmissor([
            'tipoDeposito' => $tipoDepositoA,
        ]);
        Phactory::focoTransmissor([
            'tipoDeposito' => $tipoDepositoA,
        ]);
        Phactory::focoTransmissor([
            'tipoDeposito' => $tipoDepositoB,
        ]);

        $this->assertEquals(2, FocoTransmissor::find()->doTipoDeposito($tipoDepositoA->id)->count());
        $this->assertEquals(1, FocoTransmissor::find()->doTipoDeposito($tipoDepositoB->id)->count());
    }

    public function testScopeDoBairro()
    {
        $bairroA = Phactory::bairro();
        $bairroB = Phactory::bairro();

        Phactory::focoTransmissor([
            'bairroQuarteirao' => Phactory::bairroQuarteirao(['bairro' => $bairroA]),
        ]);
        Phactory::focoTransmissor([
            'bairroQuarteirao' => Phactory::bairroQuarteirao(['bairro' => $bairroA]),
        ]);
        Phactory::focoTransmissor([
            'bairroQuarteirao' => Phactory::bairroQuarteirao(['bairro' => $bairroB]),
        ]);

        $this->assertEquals(2, FocoTransmissor::find()->doBairro($bairroA->id)->count());
        $this->assertEquals(1, FocoTransmissor::find()->doBairro($bairroB->id)->count());
    }

    public function testScopeDoImovelLira()
    {
        Phactory::focoTransmissor([
            'imovel' => Phactory::imovel(['imovel_lira' => true]),
        ]);
        Phactory::focoTransmissor([
            'imovel' => Phactory::imovel(['imovel_lira' => false]),
        ]);
        Phactory::focoTransmissor([
            'imovel' => Phactory::imovel(['imovel_lira' => true]),
        ]);

        $this->assertEquals(2, FocoTransmissor::find()->doImovelLira(true)->count());
        $this->assertEquals(1, FocoTransmissor::find()->doImovelLira(false)->count());
    }

    public function testScopeDaEspecieTransmissor()
    {
        $especieTransmissorA = Phactory::especieTransmissor();
        $especieTransmissorB = Phactory::especieTransmissor();

        Phactory::focoTransmissor([
            'especieTransmissor' => $especieTransmissorA,
        ]);
        Phactory::focoTransmissor([
            'especieTransmissor' => $especieTransmissorA,
        ]);
        Phactory::focoTransmissor([
            'especieTransmissor' => $especieTransmissorB,
        ]);

        $this->assertEquals(2, FocoTransmissor::find()->daEspecieDeTransmissor($especieTransmissorA->id)->count());
        $this->assertEquals(1, FocoTransmissor::find()->daEspecieDeTransmissor($especieTransmissorB->id)->count());
    }

    public function testScopeAtivo()
    {
        Phactory::focoTransmissor([]);

        Phactory::focoTransmissor([
            'data_coleta' => date("Y-m-d", strtotime("-359 days"))
        ]);

        Phactory::focoTransmissor([
            'data_coleta' => date("Y-m-d", strtotime("-361 days"))
        ]);

        $this->assertEquals(3, FocoTransmissor::find()->count());

        $this->assertEquals(2, FocoTransmissor::find()->ativo()->count());
    }
}
