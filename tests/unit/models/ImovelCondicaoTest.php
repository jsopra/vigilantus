<?php

namespace tests\unit\models;

use app\models\ImovelCondicao;
use yii\codeception\TestCase;

class ImovelCondicaoTest extends TestCase
{
    protected function getObject()
    {
        $imovel = new ImovelCondicao;
        $imovel->municipio_id = 1;
        $imovel->nome = 'Normal ' . uniqid();
        $imovel->inserido_por = 1;

        return $imovel;
    }

	public function testInsert() {
     
        $imovel = new ImovelCondicao;
        
        $this->assertFalse($imovel->save());

        $imovel->municipio_id = 1;
        $imovel->nome = 'Normal';
        $imovel->inserido_por = 1;
        
        $this->assertFalse($imovel->save());
        
        $imovel->nome = 'Normalis';
        $this->assertTrue($imovel->save());
        
        unset($imovel);
        
        $imovel = new ImovelCondicao;
        $imovel->municipio_id = 2;
        $imovel->nome = 'Normal';
        $imovel->inserido_por = 1;
        
        $this->assertTrue($imovel->save());
    }
    
    public function testUpdate() {
        
        $imovel = ImovelCondicao::find(1);
        $imovel->scenario = 'update';
        
        $this->assertInstanceOf('app\models\ImovelCondicao', $imovel);
        $this->assertFalse($imovel->save());
        
        $imovel->atualizado_por = 1;
        $imovel->nome = 'RG Lira';
        $this->assertFalse($imovel->save());
        
        $imovel->nome = 'Normal';
        $this->assertTrue($imovel->save());
        
        $imovel->nome = null;        
        $this->assertFalse($imovel->save());
        
        $imovel->nome = 'Normal';
        $this->assertTrue($imovel->save());
    }
    
    public function testDelete() {
     
        $imovel = $this->getObject();
        $this->assertTrue($imovel->save());
        $this->assertEquals(1, $imovel->delete());
    }
}