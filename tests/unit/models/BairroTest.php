<?php

namespace tests\unit\models;

use app\models\Bairro;
use Phactory;
use yii\codeception\TestCase;

class BairroTest extends TestCase
{
    public function testNaoSalvaDuplicado()
    {
        // Trava no mesmo município
        Phactory::bairro(['nome' => 'Tijuca', 'municipio_id' => 1]);
        $bairroDuplicado = Phactory::bairro(['municipio_id' => 1]);
        $bairroDuplicado->nome = 'Tijuca';
        $this->assertFalse($bairroDuplicado->save());

        // Permite com municípios diferentes
        $bairroDuplicado->municipio_id = Phactory::municipio()->id;
        $this->assertTrue($bairroDuplicado->save());
    }
}
