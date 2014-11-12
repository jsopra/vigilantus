<?php

namespace tests\unit\indicador;

use Yii;
use app\models\indicador\FocosTipoDepositoReport;
use Phactory;
use tests\TestCase;

class FocosTipoDepositoReportTest extends TestCase
{
    public function testGetData() 
    { 
        $this->_createScenario();
        
        $report = new FocosTipoDepositoReport;
        $report->ano = date('Y');   
        $data = $report->getData();

        $this->assertTrue(is_array($data));

        $this->assertEquals($data[0][1], 33.33);
        $this->assertEquals($data[1][1], 33.33);
        $this->assertEquals($data[2][1], 33.33);
    }

    public function testGetDataEspecie()
    {
    	$especieB = $this->_createScenario();
        
        $report = new FocosTipoDepositoReport;
        $report->ano = date('Y');
        $report->especie_transmissor_id = $especieB;
        $data = $report->getData();

        $this->assertTrue(is_array($data));

        $this->assertEquals($data[0][1], 0);
        $this->assertEquals($data[1][1], 0);
        $this->assertEquals($data[2][1], 100);
    }

    private function _createScenario() 
    {
        $depositoA = Phactory::depositoTipo(['municipio_id' => 1]);
        $depositoB = Phactory::depositoTipo(['municipio_id' => 1]);
        $depositoC = Phactory::depositoTipo(['municipio_id' => 1]);

        $especieA = Phactory::especieTransmissor(['municipio_id' => 1]);
        $especieB = Phactory::especieTransmissor(['municipio_id' => 1]);

        $quarteirao = Phactory::bairroQuarteirao([
            'municipio_id' => 1,
            'bairro_id' => Phactory::bairro(['municipio_id' => 1])->id,
        ]);

        Phactory::focoTransmissor([
            'tipo_deposito_id' => $depositoA,
        	'bairro_quarteirao_id' => $quarteirao->id, 
        	'especie_transmissor_id' => $especieA->id,
    	]);

        Phactory::focoTransmissor([
            'tipo_deposito_id' => $depositoB,
            'bairro_quarteirao_id' => $quarteirao->id, 
            'especie_transmissor_id' => $especieA->id,
        ]);

        Phactory::focoTransmissor([
            'tipo_deposito_id' => $depositoC,
            'bairro_quarteirao_id' => $quarteirao->id, 
            'especie_transmissor_id' => $especieB->id,
        ]);

        return $especieB->id;
    }
}
