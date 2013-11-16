<?php

class BairroTipoTest extends PDbTestCase
{
    public function testInsert() {
     
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
    
    public function testUpdate() {
        
        $bairro = BairroTipo::model()->findByPk(1);
        
        $this->assertInstanceOf('BairroTipo', $bairro);

        $this->assertFalse($bairro->save());
        
        $bairro->atualizado_por = 1;
        
        $this->assertTrue($bairro->save());
        
        $bairro->nome = null;        
        $this->assertFalse($bairro->save());
        
        $bairro->nome = 'Urbano';
        $this->assertTrue($bairro->save());
    }
    
    public function testDelete() {
     
        $bairro = BairroTipo::model()->findByPk(4);
        
        $this->assertInstanceOf('BairroTipo', $bairro);
        
        $this->assertTrue($bairro->delete());
    }
}