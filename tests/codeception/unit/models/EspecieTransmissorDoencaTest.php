<?php

namespace tests\unit\models;

use app\models\EspecieTransmissorDoenca;
use Phactory;
use fidelize\phactory\ActiveRecordTest;
use yii\db\Expression;

class EspecieTransmissorDoencaTest extends ActiveRecordTest
{
    public function testNaoSalvaDuplicado()
    {
        $cliente = Phactory::cliente();
        $doenca = Phactory::doenca();
        $especieTransmissor = Phactory::especieTransmissor();

        Phactory::especieTransmissorDoenca([
            'doenca' => $doenca,
            'especieTransmissor' => $especieTransmissor,
            'cliente' => $cliente
        ]);
        $especieTransmissorDuplicado = Phactory::especieTransmissorDoenca([
            'doenca' => $doenca,
            'cliente' => $cliente
        ]);
        $especieTransmissorDuplicado->especie_transmissor_id = $especieTransmissor->id;
        $this->assertFalse($especieTransmissorDuplicado->save());

        // Permite com municípios diferentes
        $especieTransmissorDuplicado->cliente_id = Phactory::cliente()->id;
        $this->assertTrue($especieTransmissorDuplicado->save());
    }
}
