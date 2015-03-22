<?php

namespace tests\unit\models;

use app\models\ImovelTipo;
use Phactory;
use fidelize\phactory\ActiveRecordTest;

class ImovelTipoTest extends ActiveRecordTest
{
    public function getObject()
    {
        $tipoImovel = new ImovelTipo;

        $cliente = Phactory::cliente();

        $tipoImovel->cliente_id = $cliente->id;
        $tipoImovel->nome = uniqid();
        $tipoImovel->inserido_por = 1;

        return $tipoImovel;
    }

    public function testCount()
    {
        $this->assertEquals(0, ImovelTipo::find()->excluido()->count());
        $this->assertEquals(5, ImovelTipo::find()->ativo()->count());

        $tipoImovel = $this->getObject();
        $this->assertTrue($tipoImovel->save());

        $this->assertEquals(0, ImovelTipo::find()->excluido()->count());
        $this->assertEquals(6, ImovelTipo::find()->ativo()->count());

        $tipoImovel->excluido = true;
        $this->assertTrue($tipoImovel->save());

        $this->assertEquals(1, ImovelTipo::find()->excluido()->count());
        $this->assertEquals(5, ImovelTipo::find()->ativo()->count());
    }

    public function testInsert()
    {
        $tipoImovel = new ImovelTipo;

        $this->assertFalse($tipoImovel->save());

        $cliente = Phactory::cliente();

        $tipoImovel->cliente_id = $cliente->id;
        $tipoImovel->nome = 'Residencial';
        $tipoImovel->inserido_por = 1;

        $this->assertTrue($tipoImovel->save());

        $tipoImovel = new ImovelTipo;

        $this->assertFalse($tipoImovel->save());

        $tipoImovel->cliente_id = $cliente->id;
        $tipoImovel->nome = 'Residencial';
        $tipoImovel->inserido_por = 1;

        $this->assertFalse($tipoImovel->save());

        $tipoImovel->nome = 'Residencialo';

        $this->assertTrue($tipoImovel->save());

        unset($tipoImovel);

        $tipoImovel = new ImovelTipo;

        $this->assertFalse($tipoImovel->save());

        $tipoImovel->cliente_id = Phactory::cliente()->id;
        $tipoImovel->nome = 'Residencial';
        $tipoImovel->inserido_por = 1;

        $this->assertTrue($tipoImovel->save());
    }

    public function testUpdate()
    {
        $tipoImovel = ImovelTipo::findOne(1);
        $tipoImovel->scenario = 'update';

        $this->assertInstanceOf('app\models\ImovelTipo', $tipoImovel);

        $this->assertFalse($tipoImovel->save());

        $tipoImovel->atualizado_por = 1;

        $tipoImovel->nome = 'Comercial';

        $this->assertFalse($tipoImovel->save());

        $tipoImovel->nome = 'Residencial';

        $this->assertTrue($tipoImovel->save());

        $tipoImovel->nome = null;
        $this->assertFalse($tipoImovel->save());

        $tipoImovel->nome = 'Residencial';
        $this->assertTrue($tipoImovel->save());
    }

    public function testScopeDaSigla()
    {
        $this->assertEquals(1, ImovelTipo::find()->daSigla('PE')->count());

        $this->assertEquals(1, ImovelTipo::find()->daSigla('TB')->count());
    }
}
