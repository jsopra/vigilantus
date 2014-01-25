<?php

namespace tests\unit\models;

use app\models\ImovelTipos;
use yii\codeception\TestCase;

class ImovelTiposTest extends TestCase
{
    public function getObject()
    {
        $tipoImovel = new ImovelTipos;

        $tipoImovel->municipio_id = 1;
        $tipoImovel->nome = uniqid();
        $tipoImovel->inserido_por = 1;

        return $tipoImovel;
    }

    public function testCount()
    {
        $this->assertEquals(0, ImovelTipos::find()->excluido()->count());
        $this->assertEquals(5, ImovelTipos::find()->ativo()->count());

        $tipoImovel = $this->getObject();
        $this->assertTrue($tipoImovel->save());

        $this->assertEquals(0, ImovelTipos::find()->excluido()->count());
        $this->assertEquals(6, ImovelTipos::find()->ativo()->count());

        $tipoImovel->excluido = true;
        $this->assertTrue($tipoImovel->save());

        $this->assertEquals(1, ImovelTipos::find()->excluido()->count());
        $this->assertEquals(5, ImovelTipos::find()->ativo()->count());
    }
    
    public function testInsert()
    {
        $tipoImovel = new ImovelTipos;
        
        $this->assertFalse($tipoImovel->save());

        $tipoImovel->municipio_id = 1;
        $tipoImovel->nome = 'Residencial';
        $tipoImovel->inserido_por = 1;
        
        $this->assertFalse($tipoImovel->save());
        
        $tipoImovel->nome = 'Residencialo';
        
        $this->assertTrue($tipoImovel->save());
        
        unset($tipoImovel);
        
        $tipoImovel = new ImovelTipos;
        
        $this->assertFalse($tipoImovel->save());
        
        $tipoImovel->municipio_id = 2;
        $tipoImovel->nome = 'Residencial';
        $tipoImovel->inserido_por = 1;
        
        $this->assertTrue($tipoImovel->save());
    }
    
    public function testUpdate()
    {
        $tipoImovel = ImovelTipos::find(1);
        $tipoImovel->scenario = 'update';
        
        $this->assertInstanceOf('app\models\ImovelTipos', $tipoImovel);

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
}
