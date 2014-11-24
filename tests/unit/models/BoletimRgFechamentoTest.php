<?php

namespace tests\unit\models;

use Phactory;
use app\models\BoletimRgFechamento;
use tests\TestCase;

class BoletimRgFechamentoTest extends TestCase
{
    public function testScopeDoBoletim()
    {
        $cliente = Phactory::cliente();

        $boletimRgA = Phactory::boletimRg(['cliente_id' => $cliente->id]);
        $boletimRgB = Phactory::boletimRg(['cliente_id' => $cliente->id]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRgA->id
        ]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRgA->id
        ]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRgA->id
        ]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRgB->id
        ]);

        $this->assertEquals(3, BoletimRgFechamento::find()->doBoletim($boletimRgA->id)->count());
        $this->assertEquals(1, BoletimRgFechamento::find()->doBoletim($boletimRgB->id)->count());
    }

    public function testScopeDoTipoImovel()
    {

    }

    public function testScopeDoTipoLira()
    {

    }

    public function testValidateLira()
    {

    }

    public function testIncrementaContagemImovel()
    {

    }

    public function testDecrementaContagemImovel()
    {

    }
}
