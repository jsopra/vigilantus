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
        ]);
        $quarteiraoDuplicado = Phactory::bairroQuarteirao([
            'bairro_id' => $bairro->id,
            'municipio_id' => $bairro->municipio_id,
        ]);
        
        $quarteiraoDuplicado->numero_quarteirao = '1234';
        $this->assertFalse($quarteiraoDuplicado->save());

        // Permite com bairros ou municípios diferentes
        $outroBairro = Phactory::bairro();
        $quarteiraoDuplicado->bairro_id = $outroBairro->id;
        $quarteiraoDuplicado->municipio_id = $outroBairro->municipio_id;
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
        
        $imovel = Phactory::imovel(['imovel_lira' => true]);
        
        Phactory::focoTransmissor([
            'imovel_id' => $imovel->id
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
}
