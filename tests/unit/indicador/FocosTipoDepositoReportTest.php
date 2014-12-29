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
        $cliente = Phactory::cliente();

        $depositoA = Phactory::depositoTipo(['cliente_id' => $cliente->id]);
        $depositoB = Phactory::depositoTipo(['cliente_id' => $cliente->id]);
        $depositoC = Phactory::depositoTipo(['cliente_id' => $cliente->id]);

        $especieA = Phactory::especieTransmissor(['cliente_id' => $cliente->id]);
        $especieB = Phactory::especieTransmissor(['cliente_id' => $cliente->id]);

        $quarteirao = Phactory::bairroQuarteirao([
            'cliente_id' => $cliente->id,
            'bairro_id' => Phactory::bairro(['cliente_id' => $cliente->id])->id,
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
