<?php

namespace tests\unit\indicador;

use Yii;
use app\models\indicador\ResumoFocosReport;
use Phactory;
use perspectiva\phactory\Test;

class ResumoFocosReportTest extends Test
{
    public function testGetData()
    {
        $this->_createScenario();

        $report = new ResumoFocosReport;
        $report->especie_transmissor_id = null;

        $report->load([]);

        $data = $report->getData();

        $this->assertTrue(is_array($data));

        $this->assertEquals($data[6][1], 2);

        $this->assertEquals($data[3][1], 2);
    }

    public function testGetDataEspecie()
    {
    	$especieB = $this->_createScenario();

        $report = new ResumoFocosReport;
        $report->especie_transmissor_id = $especieB;

        $report->load([]);

        $data = $report->getData();

        $this->assertTrue(is_array($data));

        $this->assertEquals($data[6][1], 1);

        $this->assertEquals($data[3][1], 0);
    }

    public function testGetDataPercentual()
    {
        $this->_createScenario();

        $report = new ResumoFocosReport;
        $report->especie_transmissor_id = null;

        $report->load([]);

        $data = $report->getDataPercentual();

        $this->assertTrue(is_array($data));

        $this->assertEquals($data[6][1], 50);

        $this->assertEquals($data[3][1], 50);
    }

    public function testGetDataEspeciePercentual()
    {
        $especieB = $this->_createScenario();

        $report = new ResumoFocosReport;
        $report->especie_transmissor_id = $especieB;

        $report->load([]);

        $data = $report->getDataPercentual();

        $this->assertTrue(is_array($data));

        $this->assertEquals($data[6][1], 50);

        $this->assertEquals($data[3][1], 0);
    }

    private function _createScenario()
    {
        $cliente = Phactory::cliente();

        Phactory::bairro(['cliente_id' => $cliente->id]);
        $bairro = Phactory::bairro(['cliente_id' => $cliente->id]);

        $quarteirao = Phactory::bairroQuarteirao([
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairro->id,
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
