<?php

namespace tests\unit\models;

use app\models\BairroTipo;
use yii\codeception\TestCase;

class BairroTipoTest extends TestCase
{
    public function testInsert()
    {
        $bairro = new BairroTipo;

        $this->assertFalse($bairro->save());

        $bairro->municipio_id = 1;
        $bairro->nome = 'Rural';
        $bairro->inserido_por = 1;

        $this->assertFalse($bairro->save());

        $bairro->nome = 'teste';

        $this->assertTrue($bairro->save());

        unset($bairro);

        $bairro = new BairroTipo;

        $this->assertFalse($bairro->save());

        $bairro->municipio_id = 2;
        $bairro->nome = 'teste';
        $bairro->inserido_por = 1;

        $this->assertTrue($bairro->save());
    }

    public function testUpdate()
    {
        $bairro = BairroTipo::find(1);
        $bairro->scenario = 'update';

        $this->assertInstanceOf('app\models\BairroTipo', $bairro);
        $this->assertFalse($bairro->save());

        $bairro->atualizado_por = 1;
        $this->assertTrue($bairro->save());

        $bairro->nome = null;
        $this->assertFalse($bairro->save());

        $bairro->nome = 'Urbano';
        $this->assertTrue($bairro->save());
    }

    public function testDelete()
    {
        $bairro = BairroTipo::find(1);
        $this->assertInstanceOf('app\models\BairroTipo', $bairro);
        $this->assertEquals(1, $bairro->delete());

        $bairro = BairroTipo::find(2);
        $this->assertInstanceOf('app\models\BairroTipo', $bairro);
        $this->setExpectedException('\Exception');
        $bairro->delete();
    }
}
