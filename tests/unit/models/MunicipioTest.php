<?php

namespace tests\unit\models;

use app\models\Municipio;
use Phactory;
use tests\TestCase;

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
        $this->assertEquals(0, count($municipio->getCoordenadasBairros([1,2,3,4,5])));


        $bairro = Phactory::bairro();
        $bairro->municipio_id = 1;
        $bairro->coordenadasJson = '[{"k":-27.102609121292,"A":-52.61861085891701},{"k":-27.103831607374,"A":-52.61818170547497},{"k":-27.103334974014,"A":-52.61667966842697},{"k":-27.102265294676,"A":-52.617065906525},{"k":-27.102609121292,"A":-52.61861085891701}]';
        $this->assertTrue($bairro->save());

        $municipio = Municipio::findOne(1);
        $this->assertEquals(1, count($municipio->getCoordenadasBairros([])));
        $this->assertEquals(1, count($municipio->getCoordenadasBairros([2,3,4,5])));
        $this->assertEquals(0, count($municipio->getCoordenadasBairros([1])));
    }
}
