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
        $cliente = Phactory::cliente();

        $tipoImovelA = Phactory::imovelTipo(['cliente_id' => $cliente->id]);
        $tipoImovelB = Phactory::imovelTipo(['cliente_id' => $cliente->id]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'imovel_tipo_id' => $tipoImovelA->id,
        ]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'imovel_tipo_id' => $tipoImovelA->id,
        ]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'imovel_tipo_id' => $tipoImovelA->id,
        ]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'imovel_tipo_id' => $tipoImovelB->id,
        ]);

        $this->assertEquals(3, BoletimRgFechamento::find()->doTipoDeImovel($tipoImovelA->id)->count());
        $this->assertEquals(1, BoletimRgFechamento::find()->doTipoDeImovel($tipoImovelB->id)->count());
    }

    public function testScopeDoTipoLira()
    {
        $cliente = Phactory::cliente();

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'imovel_lira' => true,
        ]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'imovel_lira' => true,
        ]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'imovel_lira' => true,
        ]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'imovel_lira' => false,
        ]);

        $this->assertEquals(3, BoletimRgFechamento::find()->doTipoLira(true)->count());
        $this->assertEquals(1, BoletimRgFechamento::find()->doTipoLira(false)->count());
    }

    public function testValidateLira()
    {
        $cliente = Phactory::cliente();

        $boletimRg = Phactory::boletimRg(['cliente_id' => $cliente->id]);

        $tipoImovel = Phactory::imovelTipo(['cliente_id' => $cliente->id]);

        Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovel->id,
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

        $boletimRg = Phactory::boletimRg(['cliente_id' => $cliente->id]);

        $tipoImovel = Phactory::imovelTipo(['cliente_id' => $cliente->id]);

        $boletimFechamento = Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovel->id,
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

        $boletimRg = Phactory::boletimRg(['cliente_id' => $cliente->id]);

        $tipoImovel = Phactory::imovelTipo(['cliente_id' => $cliente->id]);

        $boletimFechamento = Phactory::boletimRgFechamento([
            'cliente_id' => $cliente->id,
            'boletim_rg_id' => $boletimRg->id,
            'imovel_tipo_id' => $tipoImovel->id,
            'imovel_lira' => false,
            'quantidade' => 10,
        ]);

        $this->assertEquals(10, $boletimFechamento->quantidade);

        BoletimRgFechamento::decrementaContagemImovel($boletimRg, $tipoImovel->id, false);

        $boletimFechamento->refresh();

        $this->assertEquals(9, $boletimFechamento->quantidade);
    }
}
