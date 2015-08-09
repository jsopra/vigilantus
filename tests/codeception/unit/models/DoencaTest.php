<?php

namespace tests\unit\models;

use app\models\Doencas;
use Phactory;
use perspectiva\phactory\ActiveRecordTest;
use yii\db\Expression;

class DoencaTest extends ActiveRecordTest
{
	public function testNaoSalvaDuplicado()
    {
        $cliente = Phactory::cliente();

        Phactory::doenca(['nome' => 'Dengue', 'cliente' => $cliente]);
        $especieTransmissorDuplicado = Phactory::doenca(['cliente' => $cliente]);
        $especieTransmissorDuplicado->nome = 'Dengue';
        $this->assertFalse($especieTransmissorDuplicado->save());

        // Permite com municípios diferentes
        $especieTransmissorDuplicado->cliente_id = Phactory::cliente()->id;
        $this->assertTrue($especieTransmissorDuplicado->save());
    }
}
