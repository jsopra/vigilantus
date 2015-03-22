<?php

namespace tests\unit\models;

use app\models\Municipio;
use Phactory;
use fidelize\phactory\ActiveRecordTest;

class MunicipioTest extends ActiveRecordTest
{
    public function testDelete()
    {
        $municipio = Municipio::findOne(1);
        $this->setExpectedException('Exception');
        $municipio->delete();
    }

    public function testDuplica()
    {
        $floripa = Phactory::municipio(['nome' => 'Florianópolis N']);
        $floripa2 = Phactory::municipio();
        $floripa2->nome = 'Florianópolis N';
        $this->assertFalse($floripa2->save());
        $floripa2->sigla_estado = 'CE';
        $this->assertTrue($floripa2->save());
    }

    public function testLoadCoordenadas() {

        $municipio = Municipio::findOne(1);

        $this->assertNotNull($municipio->coordenadas_area);

        $this->assertTrue($municipio->loadCoordenadas());

        $this->assertEquals(-27.097643, $municipio->latitude);

        $this->assertEquals(-52.616642, $municipio->longitude);
    }

    public function testGetCoordenadasBairros() {

        $municipio = Phactory::municipio(['nome' => 'Florianópolis B']);
        $this->assertEquals(0, count($municipio->getCoordenadasBairros([])));
        $this->assertEquals(0, count($municipio->getCoordenadasBairros([0])));

        $bairro = Phactory::bairro(['municipio' => $municipio]);

        $this->assertEquals(1, count($municipio->getCoordenadasBairros([0])));
        $this->assertEquals(0, count($municipio->getCoordenadasBairros([$bairro->id])));
    }
}
