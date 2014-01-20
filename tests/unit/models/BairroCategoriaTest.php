<?php

namespace tests\unit\models;

use app\models\BairroCategoria;
use yii\codeception\TestCase;

class BairroCategoriaTest extends TestCase
{
    public function testInsert()
    {
        $bairro = new BairroCategoria;

        $this->assertFalse($bairro->save());

        $bairro->municipio_id = 1;
        $bairro->nome = 'Rural';
        $bairro->inserido_por = 1;

        $this->assertFalse($bairro->save());

        $bairro->nome = 'teste';

        $this->assertTrue($bairro->save());

        unset($bairro);

        $bairro = new BairroCategoria;

        $this->assertFalse($bairro->save());

        $bairro->municipio_id = 2;
        $bairro->nome = 'teste';
        $bairro->inserido_por = 1;

        $this->assertTrue($bairro->save());
    }

    public function testUpdate()
    {
        $bairro = BairroCategoria::find(1);
        $bairro->scenario = 'update';

        $this->assertInstanceOf('app\models\BairroCategoria', $bairro);
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
        $bairro = BairroCategoria::find(1);
        $this->assertInstanceOf('app\models\BairroCategoria', $bairro);
        $this->assertEquals(1, $bairro->delete());

        $bairro = BairroCategoria::find(2);
        $this->assertInstanceOf('app\models\BairroCategoria', $bairro);
        $this->setExpectedException('\Exception');
        $bairro->delete();
    }
}
