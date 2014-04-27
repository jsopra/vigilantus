<?php

namespace tests\unit\models;

use Phactory;
use yii\codeception\TestCase;

class EspecieTransmissorTest extends TestCase
{
    public function testNaoSalvaDuplicado()
    {
        // Trava no mesmo município
        Phactory::especieTransmissor(['nome' => 'Aedes Aegypti', 'municipio_id' => 1]);
        $especieTransmissorDuplicado = Phactory::especieTransmissor(['municipio_id' => 1]);
        $especieTransmissorDuplicado->nome = 'Aedes Aegypti';
        $this->assertFalse($especieTransmissorDuplicado->save());

        // Permite com municípios diferentes
        $especieTransmissorDuplicado->municipio_id = Phactory::municipio()->id;
        $this->assertTrue($especieTransmissorDuplicado->save());
    }
}
