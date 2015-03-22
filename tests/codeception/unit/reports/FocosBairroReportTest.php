<?php

namespace tests\unit\report;

use Yii;
use app\models\report\FocosBairroReport;
use Phactory;
use fidelize\phactory\Test;

class FocosBairroReportTest extends Test
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
