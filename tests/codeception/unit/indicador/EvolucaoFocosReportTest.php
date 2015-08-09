<?php

namespace tests\unit\indicador;

use Yii;
use app\models\indicador\EvolucaoFocosReport;
use Phactory;
use perspectiva\phactory\Test;

class EvolucaoFocosReportTest extends Test
{
    public function testGetData()
    {
        $this->_createScenario();

        $report = new EvolucaoFocosReport;
        $report->especie_transmissor_id = null;
        $data = $report->getData();

        $this->assertTrue(is_array($data));
        $this->assertEquals($data[4][4], 2);
        $this->assertEquals($data[12][1], 2);
    }

    public function testGetDataEspecie()
    {
    	$especieB = $this->_createScenario();

        $report = new EvolucaoFocosReport;
        $report->especie_transmissor_id = $especieB;

        $data = $report->getData();

        $this->assertTrue(is_array($data));
        $this->assertEquals($data[4][4], 1);
        $this->assertEquals($data[12][1], 0);
    }

    private function _createScenario()
    {
        $cliente = Phactory::cliente();

        $quarteirao = Phactory::bairroQuarteirao([
            'cliente_id' => $cliente->id,
            'bairro_id' => Phactory::bairro(['cliente_id' => $cliente->id])->id,
        ]);

        $especieA = Phactory::especieTransmissor(['cliente_id' => $cliente->id]);
        $especieB = Phactory::especieTransmissor(['cliente_id' => $cliente->id]);

        $data = date('01/04/Y');

        Phactory::focoTransmissor([
        	'bairro_quarteirao_id' => $quarteirao->id,
        	'especie_transmissor_id' => $especieA->id,
        	'data_entrada' => $data,
    	]);

        Phactory::focoTransmissor([
        	'bairro_quarteirao_id' => $quarteirao->id,
        	'especie_transmissor_id' => $especieB->id,
        	'data_entrada' => $data,
    	]);


    	$data = '01/12/' . (date('Y') - 3);

        Phactory::focoTransmissor([
        	'bairro_quarteirao_id' => $quarteirao->id,
        	'especie_transmissor_id' => $especieA->id,
        	'data_entrada' => $data,
    	]);

        Phactory::focoTransmissor([
        	'bairro_quarteirao_id' => $quarteirao->id,
        	'especie_transmissor_id' => $especieA->id,
        	'data_entrada' => $data,
    	]);

        return $especieB->id;
    }
}
