<?php

namespace tests\unit\models;

use app\models\BairroQuarteirao;
use yii\codeception\TestCase;

class BairroQuarteiraoTest extends TestCase
{
    public function testInsert()
    {
        $bairro = new BairroQuarteirao;

        $this->assertFalse($bairro->save());

        $bairro->municipio_id = 1;
        $bairro->bairro_id = 1;
        $bairro->inserido_por = 1;

        $this->assertFalse($bairro->save());

        $bairro->numero_quarteirao = 123;
        $bairro->numero_quarteirao_2 = 123;
        
        $this->assertTrue($bairro->save());

        $this->assertEquals('123', $bairro->getDsQuarteirao());
        
        unset($bairro);

        $bairro = new BairroQuarteirao;
        
        $this->assertFalse($bairro->save());

        $bairro->municipio_id = 1;
        $bairro->bairro_id = 1;
        $bairro->inserido_por = 1;
        $bairro->numero_quarteirao = 123;
        $bairro->numero_quarteirao_2 = 1234;
        
        $this->assertFalse($bairro->save());

        $bairro->municipio_id = 1;
        $bairro->bairro_id = 1;
        $bairro->inserido_por = 1;
        $bairro->numero_quarteirao = 1234;
        $bairro->numero_quarteirao_2 = 123;
        
        $this->assertFalse($bairro->save());

        $bairro->municipio_id = 1;
        $bairro->bairro_id = 1;
        $bairro->inserido_por = 1;
        $bairro->numero_quarteirao = 1234;
        $bairro->numero_quarteirao_2 = 1234;
        $bairro->seq = 1;

        $this->assertTrue($bairro->save());
        
        $this->assertEquals('1234-1', $bairro->getDsQuarteirao());
    }

    public function testUpdate()
    {
        $bairro = new BairroQuarteirao;

        $this->assertFalse($bairro->save());

        $bairro->municipio_id = 1;
        $bairro->bairro_id = 1;
        $bairro->inserido_por = 1;
        $bairro->numero_quarteirao = 12344;
        $bairro->numero_quarteirao_2 = 12344;

        $this->assertTrue($bairro->save());

        $bairro->refresh();
        
        $bairro->scenario = 'update'; 

        $this->assertFalse($bairro->save());
        
        $bairro->atualizado_por = 1;
        $this->assertTrue($bairro->save());

        $bairro->numero_quarteirao = null;
        $this->assertFalse($bairro->save());
        
        $bairro->numero_quarteirao = 1234;
        $this->assertFalse($bairro->save());

        $bairro->refresh();
        
        $bairro->numero_quarteirao = 12;
        $this->assertTrue($bairro->save());
    }

    public function testDelete()
    {
        $bairro = new BairroQuarteirao;

        $this->assertFalse($bairro->save());

        $bairro->municipio_id = 1;
        $bairro->bairro_id = 1;
        $bairro->inserido_por = 1;
        $bairro->numero_quarteirao = 12344;
        $bairro->numero_quarteirao_2 = 12344;

        $this->assertTrue($bairro->save());
        $this->assertTrue((bool) $bairro->delete());
    }
}
