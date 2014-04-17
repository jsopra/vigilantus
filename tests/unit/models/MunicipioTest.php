<?php

namespace tests\unit\models;

use app\models\Municipio;
use Phactory;
use yii\codeception\TestCase;

class MunicipioTest extends TestCase
{
    public function testDelete()
    {
        $municipio = Municipio::findOne(1);
        $this->setExpectedException('Exception');
        $municipio->delete();
    }

    public function testDuplica()
    {
        $floripa = Phactory::municipio(['nome' => 'Florianópolis']);
        $floripa2 = Phactory::municipio();
        $floripa2->nome = 'Florianópolis';
        $this->assertFalse($floripa2->save());
        $floripa2->sigla_estado = 'CE';
        $this->assertTrue($floripa2->save());
    }
}
