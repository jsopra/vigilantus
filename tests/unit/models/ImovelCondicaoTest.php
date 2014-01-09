<?php

namespace tests\unit\models;

use app\models\ImovelCondicao;
use yii\codeception\TestCase;

class ImovelCondicaoTest extends TestCase
{
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
        
        $this->assertFalse($imovel->save());
        
        $imovel->municipio_id = 2;
        $imovel->nome = 'Normal';
        $imovel->inserido_por = 1;
        
        $this->assertTrue($imovel->save());
    }
    
    public function testUpdate() {
        
        $imovel = ImovelCondicao::find(1);
        
        $this->assertInstanceOf('app\models\ImovelCondicao', $imovel);

        $this->assertFalse($imovel->save());
        
        $imovel->atualizado_por = 1;
        
        $imovel->nome = 'Área de Foco';
        
        $this->assertFalse($imovel->save());
        
        $imovel->nome = 'Normal';
        
        $this->assertTrue($imovel->save());
        
        $imovel->nome = null;        
        $this->assertFalse($imovel->save());
        
        $imovel->nome = 'Normal';
        $this->assertTrue($imovel->save());
    }
    
    public function testDelete() {
     
        $imovel = ImovelCondicao::find(5);
        
        $this->assertInstanceOf('app\models\ImovelCondicao', $imovel);
        
        $this->assertTrue($imovel->delete());
    }
}