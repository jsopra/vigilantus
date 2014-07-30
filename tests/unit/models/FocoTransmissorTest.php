<?php

namespace tests\unit\models;

use Yii;
use Phactory;
use tests\TestCase;
use app\models\FocoTransmissor;

class FocoTransmissorTest extends TestCase
{
    public function testIsAtivo()
    {
        $foco = Phactory::focoTransmissor([]);
        $this->assertTrue($foco->isAtivo());
        
        $foco = Phactory::focoTransmissor([
            'data_coleta' => date("Y-m-d", strtotime("-360 days"))
        ]);
        $this->assertTrue($foco->isAtivo());
        
        $foco = Phactory::focoTransmissor([
            'data_coleta' => date("Y-m-d", strtotime("-361 days"))
        ]);
        $this->assertFalse($foco->isAtivo());
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
}
