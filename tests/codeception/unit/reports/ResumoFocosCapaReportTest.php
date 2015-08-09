<?php

namespace tests\unit\report;

use Yii;
use app\models\report\ResumoFocosCapaReport;
use Phactory;
use perspectiva\phactory\Test;

class ResumoFocosCapaReportTest extends Test
{
    public function testGetEspecieTransmissor()
    {
        $this->_createScenario();

        $report = new ResumoFocosCapaReport;
        $this->assertEquals(count($report->getEspecieTransmissor()), 2);
    }

    public function testGetTiposDepositos()
    {
        $this->_createScenario();

        $report = new ResumoFocosCapaReport;
        $this->assertEquals(count($report->getTiposDepositos()), 2);
    }

    public function testGetFormasFoco()
    {
        $report = new ResumoFocosCapaReport;
        $this->assertEquals($report->getFormasFoco(), [
            'quantidade_forma_aquatica' => 'Aquática',
            'quantidade_forma_adulta' => 'Adulta',
            'quantidade_ovos' => 'Ovos',
        ]);
    }

    public function testGetQuantidadeFocosTipoDeposito()
    {
        $this->_createScenario();

        $report = new ResumoFocosCapaReport;

        $especie = $deposito = 1;
        $this->assertEquals($report->getQuantidadeFocosTipoDeposito(date('Y'), $especie, $deposito), 2);

        $especie = $deposito = 2;
        $this->assertEquals($report->getQuantidadeFocosTipoDeposito(date('Y'), $especie, $deposito), 1);

        $especie = 1;
        $deposito = 2;
        $this->assertEquals($report->getQuantidadeFocosTipoDeposito(date('Y'), $especie, $deposito), 1);
    }

    public function testGetPercentualFocosTipoDeposito()
    {
        $this->_createScenario();

        $report = new ResumoFocosCapaReport;

        $especie = $deposito = 1;
        $this->assertEquals($report->getPercentualFocosTipoDeposito(date('Y'), $especie, $deposito), 66.67);

        $especie = $deposito = 2;
        $this->assertEquals($report->getPercentualFocosTipoDeposito(date('Y'), $especie, $deposito), 100);

        $especie = 1;
        $deposito = 2;
        $this->assertEquals($report->getPercentualFocosTipoDeposito(date('Y'), $especie, $deposito), 33.33);
    }

    public function testGetQuantidadeFocosFormaFoco()
    {
        $this->_createScenario();

        $report = new ResumoFocosCapaReport;

        $especie = 1;
        $this->assertEquals($report->getQuantidadeFocosFormaFoco(date('Y'), $especie, 'quantidade_forma_aquatica'), 2);

        $especie = 2;
        $this->assertEquals($report->getQuantidadeFocosFormaFoco(date('Y'), $especie, 'quantidade_forma_adulta'), 1);

        $especie = 1;
        $this->assertEquals($report->getQuantidadeFocosFormaFoco(date('Y'), $especie, 'quantidade_ovos'), 1);
    }

    public function testGetPercentualFocosFormaFoco()
    {
        $this->_createScenario();

        $report = new ResumoFocosCapaReport;

        $especie = 1;
        $this->assertEquals($report->getPercentualFocosFormaFoco(date('Y'), $especie, 'quantidade_forma_aquatica'), 66.67);

        $especie = 2;
        $this->assertEquals($report->getPercentualFocosFormaFoco(date('Y'), $especie, 'quantidade_forma_adulta'), 100);

        $especie = 1;
        $this->assertEquals($report->getPercentualFocosFormaFoco(date('Y'), $especie, 'quantidade_ovos'), 33.33);
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

        $depositoA = Phactory::depositoTipo(['cliente_id' => $cliente->id]);
        $depositoB = Phactory::depositoTipo(['cliente_id' => $cliente->id]);

        Phactory::focoTransmissor([
            'tipo_deposito_id' => $depositoA->id,
        	'bairro_quarteirao_id' => $quarteirao->id,
        	'especie_transmissor_id' => $especieA->id,
            'quantidade_ovos' => 0,
            'quantidade_forma_adulta' => 0,
            'quantidade_forma_aquatica' => 2,
    	]);

        Phactory::focoTransmissor([
            'tipo_deposito_id' => $depositoA->id,
            'bairro_quarteirao_id' => $quarteirao->id,
            'especie_transmissor_id' => $especieA->id,
            'quantidade_ovos' => 0,
            'quantidade_forma_adulta' => 0,
            'quantidade_forma_aquatica' => 2,
        ]);

        Phactory::focoTransmissor([
            'tipo_deposito_id' => $depositoB->id,
            'bairro_quarteirao_id' => $quarteirao->id,
            'especie_transmissor_id' => $especieB->id,
            'quantidade_ovos' => 0,
            'quantidade_forma_adulta' => 2,
            'quantidade_forma_aquatica' => 0,
        ]);

        Phactory::focoTransmissor([
            'tipo_deposito_id' => $depositoB->id,
            'bairro_quarteirao_id' => $quarteirao->id,
            'especie_transmissor_id' => $especieA->id,
            'quantidade_ovos' => 2,
            'quantidade_forma_adulta' => 0,
            'quantidade_forma_aquatica' => 0,
        ]);
    }
}
