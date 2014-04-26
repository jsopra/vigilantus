<?php

namespace tests\unit\models;

use app\models\BairroQuarteirao;
use Phactory;
use yii\codeception\TestCase;

class BairroQuarteiraoTest extends TestCase
{
    public function testDescricaoFormatada()
    {
        $quarteirao = Phactory::bairroQuarteirao(
            [
                'numero_quarteirao' => 1234,
                'numero_quarteirao_2' => 5678,
                'seq' => 7,
            ]
        );

        $this->assertEquals('1234-7', $quarteirao->getDsNumero());
    }

    public function testNaoSalvaDuplicado()
    {
        // Trava no mesmo município/bairro
        $bairro = Phactory::bairro();

        Phactory::bairroQuarteirao([
            'numero_quarteirao' => 1234,
            'bairro_id' => $bairro->id,
            'municipio_id' => $bairro->municipio_id,
        ]);
        $quarteiraoDuplicado = Phactory::bairroQuarteirao([
            'bairro_id' => $bairro->id,
            'municipio_id' => $bairro->municipio_id,
        ]);
        $quarteiraoDuplicado->numero_quarteirao = 1234;
        $this->assertFalse($quarteiraoDuplicado->save());

        // Permite com bairros ou municípios diferentes
        $outroBairro = Phactory::bairro();
        $quarteiraoDuplicado->bairro_id = $outroBairro->id;
        $quarteiraoDuplicado->municipio_id = $outroBairro->municipio_id;
        $this->assertTrue($quarteiraoDuplicado->save());
    }
}
