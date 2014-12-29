<?php

namespace tests\unit\models;

use app\models\BairroQuarteirao;
use Phactory;
use tests\TestCase;

class BairroQuarteiraoTest extends TestCase
{
    public function testObtemNumeroComSequencia()
    {
        $quarteirao = Phactory::bairroQuarteirao(
            [
                'numero_quarteirao' => '1234',
                'numero_quarteirao_2' => '5678',
                'seq' => 7,
            ]
        );

        $this->assertEquals('1234-7', $quarteirao->getNumero_sequencia());
    }

    public function testNaoSalvaDuplicado()
    {
        // Trava no mesmo município/bairro
        $bairro = Phactory::bairro();

        Phactory::bairroQuarteirao([
            'numero_quarteirao' => '1234',
            'bairro_id' => $bairro->id,
            'municipio_id' => $bairro->municipio_id,
            'cliente_id' => $bairro->cliente_id,
        ]);
        $quarteiraoDuplicado = Phactory::bairroQuarteirao([
            'bairro_id' => $bairro->id,
            'municipio_id' => $bairro->municipio_id,
            'cliente_id' => $bairro->cliente_id,
        ]);
        
        $quarteiraoDuplicado->numero_quarteirao = '1234';
        $this->assertFalse($quarteiraoDuplicado->save());

        // Permite com bairros ou municípios diferentes
        $outroBairro = Phactory::bairro();
        $quarteiraoDuplicado->bairro_id = $outroBairro->id;
        $quarteiraoDuplicado->municipio_id = $outroBairro->municipio_id;
        $quarteiraoDuplicado->cliente_id = $outroBairro->cliente_id;
        $this->assertTrue($quarteiraoDuplicado->save());
    }
    
    public function testSaveSemCoordenadas() {
        
        $quarteirao = Phactory::bairroQuarteirao();
        $quarteirao->coordenadasJson = null;
        
        $this->assertFalse($quarteirao->save());
        
        $quarteirao->coordenadasJson = '[{"A":"-27.106734958317","k":"-52.615531682968"},{"A":"-27.105665311467","k":"-52.614759206772"},{"A":"-27.105550705842","k":"-52.614244222641"},{"A":"-27.106524849921","k":"-52.614072561264"},{"A":"-27.106734958317","k":"-52.615531682968"}]';
        $this->assertTrue($quarteirao->save());
    }
    
    public function testLoadCoordenadas() {
        
        $quarteirao = Phactory::bairroQuarteirao();
        
        $this->assertNotNull($quarteirao->coordenadas_area);
        
        $this->assertTrue($quarteirao->loadCoordenadas());
        
        $this->assertNotNull($quarteirao->coordenadas);
        
        $this->assertEquals(5, count($quarteirao->coordenadas));
        
        $arrayCoordenadas = [
            [-27.106734958317,-52.615531682968],
            [-27.105665311467,-52.614759206772],
            [-27.105550705842,-52.614244222641],
            [-27.106524849921,-52.614072561264],
            [-27.106734958317,-52.615531682968]
        ];
        
        $this->assertEquals($arrayCoordenadas, $quarteirao->coordenadas);
    }
    
    public function testGetCoordenadas() {
        
        $bairro = Phactory::bairro();
        
        $quarteirao = Phactory::bairroQuarteirao();
        $quarteirao->bairro_id = $bairro->id;
        $this->assertTrue($quarteirao->save());
        
        $quarteiroes = BairroQuarteirao::find()->doBairro($bairro->id)->comCoordenadas();
        
        $this->assertEquals(1, count(BairroQuarteirao::getCoordenadas($quarteiroes)));

        $quarteiroes = BairroQuarteirao::find()->queNao($quarteirao->id)->doBairro($bairro->id)->comCoordenadas();
        
        $this->assertEquals(0, count(BairroQuarteirao::getCoordenadas($quarteiroes)));
    }
    
    public function testValidateFocosAtivos() {
        
        $cliente = Phactory::cliente();

        $imovel = Phactory::imovel([
            'imovel_lira' => true,
            'cliente_id' => $cliente->id,
        ]);
        
        Phactory::focoTransmissor([
            'imovel_id' => $imovel->id,
            'cliente_id' => $cliente->id,
        ]);
        
        $quarteiroes = BairroQuarteirao::find()->comFocosAtivos()->all();
        $this->assertEquals(1, count($quarteiroes));
        
        $quarteiroes = BairroQuarteirao::find()->comFocosAtivos(true)->all();
        $this->assertEquals(1, count($quarteiroes));
        
        $quarteiroes = BairroQuarteirao::find()->comFocosAtivos(false)->all();
        $this->assertEquals(0, count($quarteiroes));
    }
    
    public function testNumeroQuarteirao() 
    {
        Phactory::bairroQuarteirao(['numero_quarteirao' => '123-AF', 'numero_quarteirao_2' => '120']);
        
        $quarteiroes = BairroQuarteirao::find()->doNumero('123-AF')->all();
        $this->assertEquals(1, count($quarteiroes));
        
        $quarteiroes = BairroQuarteirao::find()->doNumero('120')->all();
        $this->assertEquals(0, count($quarteiroes));
    }
    
    public function testNumerosQuarteiroes() 
    {
        Phactory::bairroQuarteirao(['numero_quarteirao' => '123-AF', 'numero_quarteirao_2' => '120']);
        
        $quarteiroes = BairroQuarteirao::find()->doNumero('120')->all();
        $this->assertEquals(0, count($quarteiroes));
        
        $quarteiroes = BairroQuarteirao::find()->dosNumeros('120')->all();
        $this->assertEquals(1, count($quarteiroes));
    }
    
    public function testGetIDsAreaTratamento()
    {
        $cliente = Phactory::cliente();
        
        $bairro = Phactory::bairro(['cliente_id' => $cliente->id]);

        $quarteiraoA = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.084606946049","k":"-52.610427439213"},{"A":"-27.085101274411","k":"-52.610169947147"},{"A":"-27.084843364233","k":"-52.608764469624"},{"A":"-27.08433709438","k":"-52.608957588673"},{"A":"-27.084556796673","k":"-52.61054545641"},{"A":"-27.084606946049","k":"-52.610427439213"}]',
            'coordenadas_area' => '0103000020E610000001000000060000007E4B02CDA8153BC01C00807C224E4AC073717632C9153BC0CCFF7F0C1A4E4AC0D1A9724BB8153BC03D0080FEEB4D4AC0A733A61D97153BC033008052F24D4AC0745DA483A5153BC04D00805A264E4AC07E4B02CDA8153BC01C00807C224E4AC0',
        ]);
        
        $quarteiraoB = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.084977095511","k":"-52.611755132675"},{"A":"-27.085550227749","k":"-52.61160492897"},{"A":"-27.085110826628","k":"-52.610210180283"},{"A":"-27.084614110243","k":"-52.610446214676"},{"A":"-27.084977095511","k":"-52.611755132675"}]',
            'coordenadas_area' => '0103000020E61000000100000005000000FA20160FC1153BC0E8FFFFFD4D4E4AC0E958A69EE6153BC0D1FFFF11494E4AC01DE7B8D2C9153BC03900005E1B4E4AC019463445A9153BC00E00001A234E4AC0FA20160FC1153BC0E8FFFFFD4D4E4AC0',
        ]);
        
        $quarteiraoC = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.082856480737","k":"-52.613750696182"},{"A":"-27.083945450094","k":"-52.613471746445"},{"A":"-27.083677984917","k":"-52.612291574478"},{"A":"-27.082589012961","k":"-52.612570524216"},{"A":"-27.082856480737","k":"-52.613750696182"}]',
            'coordenadas_area' => '0103000020E61000000100000005000000EE06131536153BC0DDFFFF618F4E4AC03ECDF2727D153BC02A00003E864E4AC01B9DA0EB6B153BC0EBFFFF915F4E4AC02AADB58D24153BC02A0000B6684E4AC0EE06131536153BC0DDFFFF618F4E4AC0',
        ]);
        
        $quarteiraoD = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.08745109535","k":"-52.61846601963"},{"A":"-27.087260054838","k":"-52.618723511696"},{"A":"-27.085712614683","k":"-52.616663575172"},{"A":"-27.085091722193","k":"-52.61628806591"},{"A":"-27.084614110243","k":"-52.61618077755"},{"A":"-27.083639775554","k":"-52.615730166435"},{"A":"-27.083467833258","k":"-52.615622878075"},{"A":"-27.083515595033","k":"-52.614786028862"},{"A":"-27.086252310745","k":"-52.614906728268"},{"A":"-27.086338279789","k":"-52.61604398489"},{"A":"-27.086643946967","k":"-52.617503106594"},{"A":"-27.086739467789","k":"-52.617824971676"},{"A":"-27.086920957126","k":"-52.617969810963"},{"A":"-27.08745109535","k":"-52.61846601963"}]',
            'coordenadas_area' => '0103000020E6100000010000000E0000001387EA3163163BC0C3FFFFE4294F4AC0C1C6C9AC56163BC013000055324F4AC00BEB0C43F1153BC0C4FFFFD4EE4E4AC0B0FA3392C8153BC0D0FFFF86E24E4AC019463445A9153BC024000003DF4E4AC033A6946A69153BC0DEFFFF3ED04E4AC0701CDE255E153BC0320000BBCC4E4AC06A5F2D4761153BC00000004FB14E4AC0ADDAA5A114163BC02E008043B54E4AC02F16F8431A163BC002008087DA4E4AC0A09E364C2E163BC0F4FF7F570A4F4AC01B6DC98E34163BC0120080E3144F4AC07531AC7340163BC02D0080A2194F4AC01387EA3163163BC0C3FFFFE4294F4AC0',
        ]);
        
        $imovel = Phactory::imovel([
            'cliente_id' => $cliente->id,
            'bairro_quarteirao_id' => $quarteiraoC->id
        ]);
        
        $opcoesEspecie = ['cliente_id' => $cliente->id];
        $opcoesFoco = ['bairro_quarteirao_id' => $quarteiraoC->id, 'cliente_id' => $cliente->id];

        $especie = Phactory::especieTransmissor($opcoesEspecie);
        
        $opcoesFoco['especie_transmissor_id'] = $especie->id;
        Phactory::focoTransmissor($opcoesFoco);
        
        $ids = BairroQuarteirao::getIDsAreaTratamento(1);

        $this->assertTrue(in_array(1, $ids));
        $this->assertTrue(in_array(2, $ids));
        $this->assertTrue(in_array(3, $ids));
        $this->assertFalse(in_array(5, $ids));
    }

    public function testScopeDoBairro()
    {
        $cliente = Phactory::cliente();
        
        $bairroA = Phactory::bairro(['cliente_id' => $cliente->id]);
        $bairroB = Phactory::bairro(['cliente_id' => $cliente->id]);

        Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairroA->id,
            'coordenadasJson' => '[{"A":"-27.084606946049","k":"-52.610427439213"},{"A":"-27.085101274411","k":"-52.610169947147"},{"A":"-27.084843364233","k":"-52.608764469624"},{"A":"-27.08433709438","k":"-52.608957588673"},{"A":"-27.084556796673","k":"-52.61054545641"},{"A":"-27.084606946049","k":"-52.610427439213"}]',
            'coordenadas_area' => '0103000020E610000001000000060000007E4B02CDA8153BC01C00807C224E4AC073717632C9153BC0CCFF7F0C1A4E4AC0D1A9724BB8153BC03D0080FEEB4D4AC0A733A61D97153BC033008052F24D4AC0745DA483A5153BC04D00805A264E4AC07E4B02CDA8153BC01C00807C224E4AC0',
        ]);
        
        Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairroA->id,
            'coordenadasJson' => '[{"A":"-27.084977095511","k":"-52.611755132675"},{"A":"-27.085550227749","k":"-52.61160492897"},{"A":"-27.085110826628","k":"-52.610210180283"},{"A":"-27.084614110243","k":"-52.610446214676"},{"A":"-27.084977095511","k":"-52.611755132675"}]',
            'coordenadas_area' => '0103000020E61000000100000005000000FA20160FC1153BC0E8FFFFFD4D4E4AC0E958A69EE6153BC0D1FFFF11494E4AC01DE7B8D2C9153BC03900005E1B4E4AC019463445A9153BC00E00001A234E4AC0FA20160FC1153BC0E8FFFFFD4D4E4AC0',
        ]);

        $this->assertEquals(2, BairroQuarteirao::find()->doBairro($bairroA->id)->count());

        $this->assertEquals(0, BairroQuarteirao::find()->doBairro($bairroB->id)->count());
    }

    public function testScopeDaSequencia()
    {
        $cliente = Phactory::cliente();
        
        $bairro = Phactory::bairro(['cliente_id' => $cliente->id]);

        Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'seq' => 12,
            'coordenadasJson' => '[{"A":"-27.084606946049","k":"-52.610427439213"},{"A":"-27.085101274411","k":"-52.610169947147"},{"A":"-27.084843364233","k":"-52.608764469624"},{"A":"-27.08433709438","k":"-52.608957588673"},{"A":"-27.084556796673","k":"-52.61054545641"},{"A":"-27.084606946049","k":"-52.610427439213"}]',
            'coordenadas_area' => '0103000020E610000001000000060000007E4B02CDA8153BC01C00807C224E4AC073717632C9153BC0CCFF7F0C1A4E4AC0D1A9724BB8153BC03D0080FEEB4D4AC0A733A61D97153BC033008052F24D4AC0745DA483A5153BC04D00805A264E4AC07E4B02CDA8153BC01C00807C224E4AC0',
        ]);
        
        Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'seq' => 14,
            'coordenadasJson' => '[{"A":"-27.084977095511","k":"-52.611755132675"},{"A":"-27.085550227749","k":"-52.61160492897"},{"A":"-27.085110826628","k":"-52.610210180283"},{"A":"-27.084614110243","k":"-52.610446214676"},{"A":"-27.084977095511","k":"-52.611755132675"}]',
            'coordenadas_area' => '0103000020E61000000100000005000000FA20160FC1153BC0E8FFFFFD4D4E4AC0E958A69EE6153BC0D1FFFF11494E4AC01DE7B8D2C9153BC03900005E1B4E4AC019463445A9153BC00E00001A234E4AC0FA20160FC1153BC0E8FFFFFD4D4E4AC0',
        ]);

        $this->assertEquals(1, BairroQuarteirao::find()->daSequencia(12)->count());

        $this->assertEquals(0, BairroQuarteirao::find()->daSequencia(13)->count());

        $this->assertEquals(1, BairroQuarteirao::find()->daSequencia(14)->count());
    }

    public function testScopeComCoordenadas()
    {
        $cliente = Phactory::cliente();
        
        $bairro = Phactory::bairro(['cliente_id' => $cliente->id]);

        Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.084606946049","k":"-52.610427439213"},{"A":"-27.085101274411","k":"-52.610169947147"},{"A":"-27.084843364233","k":"-52.608764469624"},{"A":"-27.08433709438","k":"-52.608957588673"},{"A":"-27.084556796673","k":"-52.61054545641"},{"A":"-27.084606946049","k":"-52.610427439213"}]',
            'coordenadas_area' => '0103000020E610000001000000060000007E4B02CDA8153BC01C00807C224E4AC073717632C9153BC0CCFF7F0C1A4E4AC0D1A9724BB8153BC03D0080FEEB4D4AC0A733A61D97153BC033008052F24D4AC0745DA483A5153BC04D00805A264E4AC07E4B02CDA8153BC01C00807C224E4AC0',
        ]);

        Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.084606946049","k":"-52.610427439213"},{"A":"-27.085101274411","k":"-52.610169947147"},{"A":"-27.084843364233","k":"-52.608764469624"},{"A":"-27.08433709438","k":"-52.608957588673"},{"A":"-27.084556796673","k":"-52.61054545641"},{"A":"-27.084606946049","k":"-52.610427439213"}]',
            'coordenadas_area' => '0103000020E610000001000000060000007E4B02CDA8153BC01C00807C224E4AC073717632C9153BC0CCFF7F0C1A4E4AC0D1A9724BB8153BC03D0080FEEB4D4AC0A733A61D97153BC033008052F24D4AC0745DA483A5153BC04D00805A264E4AC07E4B02CDA8153BC01C00807C224E4AC0',
        ]);

        $this->assertEquals(2, BairroQuarteirao::find()->comCoordenadas()->count());
    }

    public function testScopeQueNao()
    {
        $cliente = Phactory::cliente();
        
        $bairro = Phactory::bairro(['cliente_id' => $cliente->id]);

        $quarteiraoA = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.084606946049","k":"-52.610427439213"},{"A":"-27.085101274411","k":"-52.610169947147"},{"A":"-27.084843364233","k":"-52.608764469624"},{"A":"-27.08433709438","k":"-52.608957588673"},{"A":"-27.084556796673","k":"-52.61054545641"},{"A":"-27.084606946049","k":"-52.610427439213"}]',
            'coordenadas_area' => '0103000020E610000001000000060000007E4B02CDA8153BC01C00807C224E4AC073717632C9153BC0CCFF7F0C1A4E4AC0D1A9724BB8153BC03D0080FEEB4D4AC0A733A61D97153BC033008052F24D4AC0745DA483A5153BC04D00805A264E4AC07E4B02CDA8153BC01C00807C224E4AC0',
        ]);

        Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.084606946049","k":"-52.610427439213"},{"A":"-27.085101274411","k":"-52.610169947147"},{"A":"-27.084843364233","k":"-52.608764469624"},{"A":"-27.08433709438","k":"-52.608957588673"},{"A":"-27.084556796673","k":"-52.61054545641"},{"A":"-27.084606946049","k":"-52.610427439213"}]',
            'coordenadas_area' => '0103000020E610000001000000060000007E4B02CDA8153BC01C00807C224E4AC073717632C9153BC0CCFF7F0C1A4E4AC0D1A9724BB8153BC03D0080FEEB4D4AC0A733A61D97153BC033008052F24D4AC0745DA483A5153BC04D00805A264E4AC07E4B02CDA8153BC01C00807C224E4AC0',
        ]);
        
        Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadas_area' => null
        ]);

        $this->assertEquals(2, BairroQuarteirao::find()->queNao($quarteiraoA->id)->count());
    }

    public function testScopeComFocosAtivos()
    {
        $cliente = Phactory::cliente();
        
        $bairro = Phactory::bairro(['cliente_id' => $cliente->id]);

        $quarteiraoA = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.084606946049","k":"-52.610427439213"},{"A":"-27.085101274411","k":"-52.610169947147"},{"A":"-27.084843364233","k":"-52.608764469624"},{"A":"-27.08433709438","k":"-52.608957588673"},{"A":"-27.084556796673","k":"-52.61054545641"},{"A":"-27.084606946049","k":"-52.610427439213"}]',
            'coordenadas_area' => '0103000020E610000001000000060000007E4B02CDA8153BC01C00807C224E4AC073717632C9153BC0CCFF7F0C1A4E4AC0D1A9724BB8153BC03D0080FEEB4D4AC0A733A61D97153BC033008052F24D4AC0745DA483A5153BC04D00805A264E4AC07E4B02CDA8153BC01C00807C224E4AC0',
        ]);
        
        $quarteiraoB = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.084977095511","k":"-52.611755132675"},{"A":"-27.085550227749","k":"-52.61160492897"},{"A":"-27.085110826628","k":"-52.610210180283"},{"A":"-27.084614110243","k":"-52.610446214676"},{"A":"-27.084977095511","k":"-52.611755132675"}]',
            'coordenadas_area' => '0103000020E61000000100000005000000FA20160FC1153BC0E8FFFFFD4D4E4AC0E958A69EE6153BC0D1FFFF11494E4AC01DE7B8D2C9153BC03900005E1B4E4AC019463445A9153BC00E00001A234E4AC0FA20160FC1153BC0E8FFFFFD4D4E4AC0',
        ]);
        
        $imovel = Phactory::imovel([
            'cliente_id' => $cliente->id,
            'bairro_quarteirao_id' => $quarteiraoB->id
        ]);
        
        $especie = Phactory::especieTransmissor(['cliente_id' => $cliente->id]);
        
        $opcoesFoco['especie_transmissor_id'] = $especie->id;

        Phactory::focoTransmissor([
            'bairro_quarteirao_id' => $quarteiraoA->id, 
            'cliente_id' => $cliente->id,
            'data_coleta' => date('d/m/Y'),
        ]);

        Phactory::focoTransmissor([
            'bairro_quarteirao_id' => $quarteiraoB->id, 
            'cliente_id' => $cliente->id,
            'data_coleta' => '01/10/2001',
        ]);

        $this->assertEquals(1, BairroQuarteirao::find()->comFocosAtivos()->count());
    }

    public function testScopeEmAreaDeTratamento()
    {
        $cliente = Phactory::cliente();
        
        $bairro = Phactory::bairro(['cliente_id' => $cliente->id]);

        $quarteiraoA = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.084606946049","k":"-52.610427439213"},{"A":"-27.085101274411","k":"-52.610169947147"},{"A":"-27.084843364233","k":"-52.608764469624"},{"A":"-27.08433709438","k":"-52.608957588673"},{"A":"-27.084556796673","k":"-52.61054545641"},{"A":"-27.084606946049","k":"-52.610427439213"}]',
            'coordenadas_area' => '0103000020E610000001000000060000007E4B02CDA8153BC01C00807C224E4AC073717632C9153BC0CCFF7F0C1A4E4AC0D1A9724BB8153BC03D0080FEEB4D4AC0A733A61D97153BC033008052F24D4AC0745DA483A5153BC04D00805A264E4AC07E4B02CDA8153BC01C00807C224E4AC0',
        ]);
        
        $quarteiraoB = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.084977095511","k":"-52.611755132675"},{"A":"-27.085550227749","k":"-52.61160492897"},{"A":"-27.085110826628","k":"-52.610210180283"},{"A":"-27.084614110243","k":"-52.610446214676"},{"A":"-27.084977095511","k":"-52.611755132675"}]',
            'coordenadas_area' => '0103000020E61000000100000005000000FA20160FC1153BC0E8FFFFFD4D4E4AC0E958A69EE6153BC0D1FFFF11494E4AC01DE7B8D2C9153BC03900005E1B4E4AC019463445A9153BC00E00001A234E4AC0FA20160FC1153BC0E8FFFFFD4D4E4AC0',
        ]);
        
        $quarteiraoC = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.082856480737","k":"-52.613750696182"},{"A":"-27.083945450094","k":"-52.613471746445"},{"A":"-27.083677984917","k":"-52.612291574478"},{"A":"-27.082589012961","k":"-52.612570524216"},{"A":"-27.082856480737","k":"-52.613750696182"}]',
            'coordenadas_area' => '0103000020E61000000100000005000000EE06131536153BC0DDFFFF618F4E4AC03ECDF2727D153BC02A00003E864E4AC01B9DA0EB6B153BC0EBFFFF915F4E4AC02AADB58D24153BC02A0000B6684E4AC0EE06131536153BC0DDFFFF618F4E4AC0',
        ]);
        
        $quarteiraoD = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
            'coordenadasJson' => '[{"A":"-27.08745109535","k":"-52.61846601963"},{"A":"-27.087260054838","k":"-52.618723511696"},{"A":"-27.085712614683","k":"-52.616663575172"},{"A":"-27.085091722193","k":"-52.61628806591"},{"A":"-27.084614110243","k":"-52.61618077755"},{"A":"-27.083639775554","k":"-52.615730166435"},{"A":"-27.083467833258","k":"-52.615622878075"},{"A":"-27.083515595033","k":"-52.614786028862"},{"A":"-27.086252310745","k":"-52.614906728268"},{"A":"-27.086338279789","k":"-52.61604398489"},{"A":"-27.086643946967","k":"-52.617503106594"},{"A":"-27.086739467789","k":"-52.617824971676"},{"A":"-27.086920957126","k":"-52.617969810963"},{"A":"-27.08745109535","k":"-52.61846601963"}]',
            'coordenadas_area' => '0103000020E6100000010000000E0000001387EA3163163BC0C3FFFFE4294F4AC0C1C6C9AC56163BC013000055324F4AC00BEB0C43F1153BC0C4FFFFD4EE4E4AC0B0FA3392C8153BC0D0FFFF86E24E4AC019463445A9153BC024000003DF4E4AC033A6946A69153BC0DEFFFF3ED04E4AC0701CDE255E153BC0320000BBCC4E4AC06A5F2D4761153BC00000004FB14E4AC0ADDAA5A114163BC02E008043B54E4AC02F16F8431A163BC002008087DA4E4AC0A09E364C2E163BC0F4FF7F570A4F4AC01B6DC98E34163BC0120080E3144F4AC07531AC7340163BC02D0080A2194F4AC01387EA3163163BC0C3FFFFE4294F4AC0',
        ]);
        
        $imovel = Phactory::imovel([
            'cliente_id' => $cliente->id,
            'bairro_quarteirao_id' => $quarteiraoC->id
        ]);
        
        $opcoesEspecie = ['cliente_id' => $cliente->id];
        $opcoesFoco = ['bairro_quarteirao_id' => $quarteiraoC->id, 'cliente_id' => $cliente->id];

        $especie = Phactory::especieTransmissor($opcoesEspecie);
        
        $opcoesFoco['especie_transmissor_id'] = $especie->id;
        Phactory::focoTransmissor($opcoesFoco);

        $this->assertEquals(4, BairroQuarteirao::find()->emAreaDeTratamento($cliente)->count());
    }
}
