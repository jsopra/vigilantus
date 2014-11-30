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
        $cliente = Phactory::cliente();

        Phactory::bairro(['cliente_id' => $cliente->id]);
        $bairroB = Phactory::bairro(['cliente_id' => $cliente->id]);
        $bairroC = Phactory::bairro(['cliente_id' => $cliente->id]);

        $quarteiraoB = Phactory::bairroQuarteirao([
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairroB->id,
        ]);

        $quarteiraoC = Phactory::bairroQuarteirao([
            'cliente_id' => $cliente->id,
            'bairro_id' => $bairroC->id,
        ]);

        $especieA = Phactory::especieTransmissor(['cliente_id' => $cliente->id]);
        $especieB = Phactory::especieTransmissor(['cliente_id' => $cliente->id]);

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
