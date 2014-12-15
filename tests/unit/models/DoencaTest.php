<?php

namespace tests\unit\models;

use app\models\Doencas;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class DoencaTest extends TestCase
{
	public function testNaoSalvaDuplicado()
    {
        $cliente = Phactory::cliente();

        Phactory::doenca(['nome' => 'Dengue', 'cliente_id' => $cliente]);
        $especieTransmissorDuplicado = Phactory::doenca(['cliente_id' => $cliente]);
        $especieTransmissorDuplicado->nome = 'Dengue';
        $this->assertFalse($especieTransmissorDuplicado->save());

        // Permite com municípios diferentes
        $especieTransmissorDuplicado->cliente_id = Phactory::cliente()->id;
        $this->assertTrue($especieTransmissorDuplicado->save());
    }
}
