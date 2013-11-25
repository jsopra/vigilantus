<?php

class ImovelCondicaoTest extends PDbTestCase
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
        
        $imovel = ImovelCondicao::model()->findByPk(1);
        
        $this->assertInstanceOf('ImovelCondicao', $imovel);

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
     
        $imovel = ImovelCondicao::model()->findByPk(5);
        
        $this->assertInstanceOf('ImovelCondicao', $imovel);
        
        $this->assertTrue($imovel->delete());
    }
}