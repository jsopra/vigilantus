<?php

namespace tests\unit\models;

use Phactory;
use perspectiva\phactory\ActiveRecordTest;
use app\models\EspecieTransmissor;

class EspecieTransmissorTest extends ActiveRecordTest
{
    public function testNaoSalvaDuplicado()
    {
        $cliente = Phactory::cliente();

        Phactory::especieTransmissor(['nome' => 'Aedes Aegypti', 'cliente' => $cliente]);
        $especieTransmissorDuplicado = Phactory::especieTransmissor(['cliente' => $cliente]);
        $especieTransmissorDuplicado->nome = 'Aedes Aegypti';
        $this->assertFalse($especieTransmissorDuplicado->save());

        // Permite com municípios diferentes
        $especieTransmissorDuplicado->cliente_id = Phactory::cliente()->id;
        $this->assertTrue($especieTransmissorDuplicado->save());
    }

    public function testGetCor()
    {
        $cliente = Phactory::cliente();

        $especieTransmissor = Phactory::especieTransmissor(['nome' => 'Aedes Aegypti', 'cliente_id' => $cliente]);
        $this->assertEquals(EspecieTransmissor::COR_FOCO_DEFAULT, $especieTransmissor->cor);

        $especieTransmissor->cor_foco_no_mapa = '#c0c0c0';
        $this->assertTrue($especieTransmissor->save());

        $this->assertEquals('#c0c0c0', $especieTransmissor->cor);
    }

    public function testScopeEspecieNome()
    {
        $cliente = Phactory::cliente();

        Phactory::especieTransmissor(['nome' => 'Aedes Aegypti', 'cliente_id' => $cliente]);
        Phactory::especieTransmissor(['nome' => 'Aedes Robervalus', 'cliente_id' => $cliente]);

        $this->assertInstanceOf("app\models\EspecieTransmissor", EspecieTransmissor::find()->doNome('Aedes Aegypti')->one());
        $this->assertNull(EspecieTransmissor::find()->doNome('Finn')->one());
        $this->assertInstanceOf("app\models\EspecieTransmissor", EspecieTransmissor::find()->doNome('Aedes Robervalus')->one());
    }

    public function testQtdeAssociaDoenca()
    {
        $cliente = Phactory::cliente();
        $doenca = Phactory::doenca(['cliente' => $cliente]);
        $especieTransmissor = Phactory::especieTransmissor(['nome' => 'Aedes Aegypti', 'cliente' => $cliente]);

        $this->assertEquals(0, count($especieTransmissor->doencasEspecie));

        Phactory::especieTransmissorDoenca(['doenca' => $doenca, 'especieTransmissor' => $especieTransmissor, 'cliente' => $cliente]);

        $especieTransmissor->refresh();

        $this->assertEquals(1, count($especieTransmissor->doencasEspecie));
    }

    public function testAssociaDoenca()
    {
        $cliente = Phactory::cliente();
        $doenca = Phactory::doenca(['cliente_id' => $cliente]);
        $especieTransmissor = Phactory::especieTransmissor(['nome' => 'Aedes Aegypti', 'cliente_id' => $cliente]);

        $this->assertEquals(0, count($especieTransmissor->doencasEspecie));

        $especieTransmissor->doencas = [$doenca->id];

        $this->assertTrue($especieTransmissor->save());

        $especieTransmissor->refresh();

        $this->assertEquals(1, count($especieTransmissor->doencasEspecie));
    }
}
