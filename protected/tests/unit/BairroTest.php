<?php

class BairroTest extends PDbTestCase
{
	public function testInsert() {
     
        $bairro = new Bairro;
        
        $this->assertFalse($bairro->save());
        
        $bairro->municipio_id = 1;
        $bairro->nome = 'teste';
        $bairro->bairro_tipo_id = 2;
        
        $this->assertFalse($bairro->save());
        
        $bairro->nome = 'teste1';
        
        $this->assertTrue($bairro->save());
        
        unset($bairro);
        
        $bairro = new Bairro;
        
        $this->assertFalse($bairro->save());
        
        $bairro->municipio_id = 2;
        $bairro->nome = 'teste';
        $bairro->bairro_tipo_id = 1;
        
        $this->assertTrue($bairro->save());
    }
    
    public function testUpdate() {
        
        $bairro = Bairro::model()->findByPk(1);
        
        $this->assertInstanceOf('Bairro', $bairro);
        
        $this->assertTrue($bairro->save());
        
        $bairro->nome = null;        
        $this->assertFalse($bairro->save());
        
        $bairro->nome = 'teste';
        $this->assertTrue($bairro->save());
    }
    
    public function testDelete() {
     
        $bairro = Bairro::model()->findByPk(4);
        
        $this->assertInstanceOf('Bairro', $bairro);
        
        $this->assertTrue($bairro->delete());
    }
}