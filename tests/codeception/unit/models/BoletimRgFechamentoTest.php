<?php

namespace tests\unit\models;

use Phactory;
use app\models\BoletimRgFechamento;
use perspectiva\phactory\ActiveRecordTest;

class BoletimRgFechamentoTest extends ActiveRecordTest
{
    public function testScopeDoBoletim()
    {
        $cliente = Phactory::cliente();

        $boletimRgA = Phactory::boletimRg(['cliente' => $cliente]);
        $boletimRgB = Phactory::boletimRg(['cliente' => $cliente]);

        Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'boletimRg' => $boletimRgA,
        ]);
        Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'boletimRg' => $boletimRgA,
        ]);
        Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'boletimRg' => $boletimRgA,
        ]);
        Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'boletimRg' => $boletimRgB,
        ]);

        $this->assertEquals(3, BoletimRgFechamento::find()->doBoletim($boletimRgA->id)->count());
        $this->assertEquals(1, BoletimRgFechamento::find()->doBoletim($boletimRgB->id)->count());
    }

    public function testScopeDoTipoImovel()
    {
        $cliente = Phactory::cliente();

        $tipoImovelA = Phactory::imovelTipo(['cliente' => $cliente]);
        $tipoImovelB = Phactory::imovelTipo(['cliente' => $cliente]);

        Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'imovelTipo' => $tipoImovelA,
        ]);

        Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'imovelTipo' => $tipoImovelA,
        ]);

        Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'imovelTipo' => $tipoImovelA,
        ]);

        Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'imovelTipo' => $tipoImovelB,
        ]);

        $this->assertEquals(3, BoletimRgFechamento::find()->doTipoDeImovel($tipoImovelA->id)->count());
        $this->assertEquals(1, BoletimRgFechamento::find()->doTipoDeImovel($tipoImovelB->id)->count());
    }

    public function testScopeDoTipoLira()
    {
        $cliente = Phactory::cliente();

        Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'imovel_lira' => true,
        ]);

        Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'imovel_lira' => true,
        ]);

        Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'imovel_lira' => true,
        ]);

        Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'imovel_lira' => false,
        ]);

        $this->assertEquals(3, BoletimRgFechamento::find()->doTipoLira(true)->count());
        $this->assertEquals(1, BoletimRgFechamento::find()->doTipoLira(false)->count());
    }

    public function testValidateLira()
    {
        $cliente = Phactory::cliente();

        $boletimRg = Phactory::boletimRg(['cliente' => $cliente]);

        $tipoImovel = Phactory::imovelTipo(['cliente' => $cliente]);

        Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'boletimRg' => $boletimRg,
            'imovelTipo' => $tipoImovel,
            'imovel_lira' => false,
            'quantidade' => 10,
        ]);

        $boletimLira = new BoletimRgFechamento;
        $boletimLira->cliente_id = $cliente->id;
        $boletimLira->boletim_rg_id = $boletimRg->id;
        $boletimLira->quantidade = 11;
        $boletimLira->imovel_tipo_id = $tipoImovel->id;
        $boletimLira->imovel_lira = true;

        $this->assertFalse($boletimLira->validate());

        $boletimLira->quantidade = 10;

        $this->assertTrue($boletimLira->validate());
    }

    public function testIncrementaContagemImovel()
    {
        $cliente = Phactory::cliente();

        $boletimRg = Phactory::boletimRg(['cliente' => $cliente]);

        $tipoImovel = Phactory::imovelTipo(['cliente' => $cliente]);

        $boletimFechamento = Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'boletimRg' => $boletimRg,
            'imovelTipo' => $tipoImovel,
            'imovel_lira' => false,
            'quantidade' => 10,
        ]);

        $this->assertEquals(10, $boletimFechamento->quantidade);

        BoletimRgFechamento::incrementaContagemImovel($boletimRg, $tipoImovel->id, false);

        $boletimFechamento->refresh();

        $this->assertEquals(11, $boletimFechamento->quantidade);
    }

    public function testDecrementaContagemImovel()
    {
        $cliente = Phactory::cliente();

        $boletimRg = Phactory::boletimRg(['cliente' => $cliente]);

        $tipoImovel = Phactory::imovelTipo(['cliente' => $cliente]);

        $boletimFechamento = Phactory::boletimRgFechamento([
            'cliente' => $cliente,
            'boletimRg' => $boletimRg,
            'imovelTipo' => $tipoImovel,
            'imovel_lira' => false,
            'quantidade' => 10,
        ]);

        $this->assertEquals(10, $boletimFechamento->quantidade);

        BoletimRgFechamento::decrementaContagemImovel($boletimRg, $tipoImovel->id, false);

        $boletimFechamento->refresh();

        $this->assertEquals(9, $boletimFechamento->quantidade);
    }
}
