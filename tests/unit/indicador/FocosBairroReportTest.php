<?php

namespace tests\unit\indicador;

use Yii;
use app\models\indicador\FocosBairroReport;
use Phactory;
use tests\TestCase;

class FocosBairroReportTest extends TestCase
{
    public function testGetData() 
    { 
        $this->_createScenario();
        
        $report = new FocosBairroReport;
        $report->ano = date('Y');
        $report->especie_transmissor_id = null;
        $data = $report->getData();

        $this->assertTrue(is_array($data));

        $this->assertEquals($data[0][1], 0);//bairro A
        $this->assertEquals($data[1][1], 2);//bairro B
        $this->assertEquals($data[2][1], 1);//bairro C
    }

    public function testGetDataEspecie()
    {
    	$especieB = $this->_createScenario();
        
        $report = new FocosBairroReport;
        $report->ano = date('Y');
        $report->especie_transmissor_id = $especieB;
        $data = $report->getData();

        $this->assertTrue(is_array($data));

        $this->assertEquals($data[0][1], 0);//bairro A
        $this->assertEquals($data[1][1], 1);//bairro B
        $this->assertEquals($data[2][1], 1);//bairro C
    }

    private function _createScenario() 
    {
        Phactory::bairro(['municipio_id' => 1]);
        $bairroB = Phactory::bairro(['municipio_id' => 1]);
        $bairroC = Phactory::bairro(['municipio_id' => 1]);

        $quarteiraoB = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'bairro_id' => $bairroB->id,
        ]);

        $quarteiraoC = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'bairro_id' => $bairroC->id,
        ]);
        
        $especieA = Phactory::especieTransmissor(['municipio_id' => 1]);
        $especieB = Phactory::especieTransmissor(['municipio_id' => 1]);

        Phactory::focoTransmissor([
        	'bairro_quarteirao_id' => $quarteiraoB->id, 
        	'especie_transmissor_id' => $especieB->id,
    	]);

        Phactory::focoTransmissor([
        	'bairro_quarteirao_id' => $quarteiraoB->id, 
        	'especie_transmissor_id' => $especieA->id,
    	]);

        Phactory::focoTransmissor([
        	'bairro_quarteirao_id' => $quarteiraoC->id, 
        	'especie_transmissor_id' => $especieB->id,
    	]);
        
        return $especieB->id;
    }
}
