<?php

namespace tests\unit\models;

use Phactory;
use tests\TestCase;
use app\models\EspecieTransmissor;

class EspecieTransmissorTest extends TestCase
{
    public function testNaoSalvaDuplicado()
    {
        // Trava no mesmo município
        Phactory::especieTransmissor(['nome' => 'Aedes Aegypti', 'municipio_id' => 1]);
        $especieTransmissorDuplicado = Phactory::especieTransmissor(['municipio_id' => 1]);
        $especieTransmissorDuplicado->nome = 'Aedes Aegypti';
        $this->assertFalse($especieTransmissorDuplicado->save());

        // Permite com municípios diferentes
        $especieTransmissorDuplicado->municipio_id = Phactory::municipio()->id;
        $this->assertTrue($especieTransmissorDuplicado->save());
    }
    
    public function testGetCor() {
        
        $especieTransmissor = Phactory::especieTransmissor(['nome' => 'Aedes Aegypti', 'municipio_id' => 1]);
        $this->assertEquals(EspecieTransmissor::COR_FOCO_DEFAULT, $especieTransmissor->cor);
        
        $especieTransmissor->cor_foco_no_mapa = '#c0c0c0';
        $this->assertTrue($especieTransmissor->save());
        
        $this->assertEquals('#c0c0c0', $especieTransmissor->cor);
    }
    
    public function testScopeEspecieNome()
    {
        Phactory::especieTransmissor(['nome' => 'Aedes Aegypti', 'municipio_id' => 1]);
        Phactory::especieTransmissor(['nome' => 'Aedes Robervalus', 'municipio_id' => 1]);
        
        $this->assertInstanceOf("app\models\EspecieTransmissor", EspecieTransmissor::find()->doNome('Aedes Aegypti')->one());
        $this->assertNull(EspecieTransmissor::find()->doNome('Finn')->one());
        $this->assertInstanceOf("app\models\EspecieTransmissor", EspecieTransmissor::find()->doNome('Aedes Robervalus')->one());
    }
}
