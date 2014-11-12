<?php

namespace tests\unit\report;

use Yii;
use app\models\report\FocosBairroReport;
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

        $report->load([]);

        $this->assertEquals(3, $report->dataProviderAreasFoco->getTotalCount());

        $data = $report->dataProviderAreasFoco->getModels();

        $this->assertEquals($data[0][13], 0);
        $this->assertEquals($data[1][13], 2);
        $this->assertEquals($data[2][13], 1);
    }

    public function testGetDataEspecie()
    {
    	$especieB = $this->_createScenario();
        
        $report = new FocosBairroReport;
        $report->ano = date('Y');
        $report->especie_transmissor_id = $especieB;

        $report->load([]);

        $this->assertEquals(3, $report->dataProviderAreasFoco->getTotalCount());

        $data = $report->dataProviderAreasFoco->getModels();

        $this->assertEquals($data[0][13], 0);
        $this->assertEquals($data[1][13], 1);
        $this->assertEquals($data[2][13], 1);
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

        $data = date('01/04/Y');

        Phactory::focoTransmissor([
        	'bairro_quarteirao_id' => $quarteiraoB->id, 
        	'especie_transmissor_id' => $especieA->id,
        	'data_entrada' => $data,
    	]);

        Phactory::focoTransmissor([
        	'bairro_quarteirao_id' => $quarteiraoB->id, 
        	'especie_transmissor_id' => $especieB->id,
        	'data_entrada' => $data,
    	]);

        Phactory::focoTransmissor([
            'bairro_quarteirao_id' => $quarteiraoC->id, 
            'especie_transmissor_id' => $especieB->id,
            'data_entrada' => $data,
        ]);
        
        return $especieB->id;
    }
}
