<?php

namespace tests\unit\models;

use app\models\Bairro;
use Phactory;
use tests\TestCase;

class BairroTest extends TestCase
{
    public function testNaoSalvaDuplicado()
    {
        // Trava no mesmo município
        Phactory::bairro(['nome' => 'Tijuca', 'municipio_id' => 1]);
        $bairroDuplicado = Phactory::bairro(['municipio_id' => 1]);
        $bairroDuplicado->nome = 'Tijuca';
        
        $this->assertFalse($bairroDuplicado->save());

        // Permite com municípios diferentes
        $bairroDuplicado->municipio_id = Phactory::municipio()->id;
        $this->assertTrue($bairroDuplicado->save());
    }
    
    public function testSaveSemCoordenadas() {
        
        $bairro = Phactory::bairro();
        $bairro->coordenadasJson = null;
        
        $this->assertFalse($bairro->save());
        
        $bairro->coordenadasJson = '[{"k":-27.102609121292,"A":-52.61861085891701},{"k":-27.103831607374,"A":-52.61818170547497},{"k":-27.103334974014,"A":-52.61667966842697},{"k":-27.102265294676,"A":-52.617065906525},{"k":-27.102609121292,"A":-52.61861085891701}]';
        $this->assertTrue($bairro->save());
    }
    
    public function testLoadCoordenadas() {
        
        $bairro = Phactory::bairro();
        
        $this->assertNotNull($bairro->coordenadas_area);
        
        $this->assertTrue($bairro->loadCoordenadas());
        
        $this->assertNotNull($bairro->coordenadas);
        
        $this->assertEquals(12, count($bairro->coordenadas));
        
        $arrayCoordenadas = [
            [-27.090154283676,-52.620005607605],
            [-27.088855233117,-52.617774009705],
            [-27.091778075689,-52.613772153854],
            [-27.091797178985,-52.615638971329],
            [-27.10027872022,-52.602034807205],
            [-27.101921467004,-52.612849473953],
            [-27.100698960064,-52.62481212616],
            [-27.089008063376,-52.625670433044],
            [-27.085760375501,-52.622172832489],
            [-27.084996199966,-52.621314525604],
            [-27.087212294659,-52.621014118195],
            [-27.090154283676,-52.620005607605]
        ];
        
        $this->assertEquals($arrayCoordenadas, $bairro->coordenadas);
    }

    public function testScopeDoNome()
    {
        Phactory::bairro(['nome' => 'Nomedobairro']);

        $this->assertEquals(Bairro::find()->doNome('Nomedobairro')->count(), 1);
        $this->assertEquals(Bairro::find()->doNome('Nome do bairro')->count(), 0);
    }

    public function testScopeComQuarteiroes()
    {
        Phactory::bairro(['nome' => 'Nomedobairro']);
        Phactory::bairroQuarteirao(['bairro_id' => Phactory::bairro()->id]);
        Phactory::bairroQuarteirao(['bairro_id' => Phactory::bairro()->id]);

        $this->assertEquals(Bairro::find()->comQuarteiroes()->count(), 2);
    }

    public function testScopeComCoordenadas()
    {
        Phactory::bairro();
        Phactory::bairro();
        
        $this->assertEquals(Bairro::find()->comCoordenadas()->count(), 2);
    }

    public function testScopeComFoco()
    {
        Phactory::bairroQuarteirao(['bairro_id' => Phactory::bairro()->id]);
        Phactory::bairroQuarteirao(['bairro_id' => Phactory::bairro()->id]);
        $quarteirao = Phactory::bairroQuarteirao(['bairro_id' => Phactory::bairro()->id]);

        $especie = Phactory::especieTransmissor(['municipio_id' => 1]);

        Phactory::focoTransmissor([
            'bairro_quarteirao_id' => $quarteirao->id, 
            'especie_transmissor_id' => $especie->id,
            'data_entrada' => date('01/04/Y'),
        ]);

        $this->assertEquals(Bairro::find()->comFoco(date('Y'))->count(), 1);

        $this->assertEquals(Bairro::find()->comFoco(date('Y') - 1)->count(), 0);
    }
}
