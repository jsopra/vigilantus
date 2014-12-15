<?php

namespace tests\unit\models;

use app\models\EspecieTransmissorDoenca;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class EspecieTransmissorDoencaTest extends TestCase
{
    public function testNaoSalvaDuplicado()
    {
        $cliente = Phactory::cliente();
        $doenca = Phactory::doenca();
        $especieTransmissor = Phactory::especieTransmissor();

        Phactory::especieTransmissorDoenca(['doenca_id' => $doenca, 'especie_transmissor_id' => $especieTransmissor, 'cliente_id' => $cliente]);
        $especieTransmissorDuplicado = Phactory::especieTransmissorDoenca(['doenca_id' => $doenca, 'cliente_id' => $cliente]);
        $especieTransmissorDuplicado->especie_transmissor_id = $especieTransmissor->id;
        $this->assertFalse($especieTransmissorDuplicado->save());

        // Permite com municípios diferentes
        $especieTransmissorDuplicado->cliente_id = Phactory::cliente()->id;
        $this->assertTrue($especieTransmissorDuplicado->save());
    }
}
