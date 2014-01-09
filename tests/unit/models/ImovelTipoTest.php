<?php

namespace tests\unit\models;

use app\models\ImovelTipo;
use yii\codeception\TestCase;

class ImovelTipoTest extends TestCase
{
    public function testCount()
    {
        $this->assertEquals(5, ImovelTipo::find()->ativo()->count());
        $this->assertEquals(1, ImovelTipo::find()->excluido()->count());
    }
    
    public function testInsert()
    {
        $imovel = new ImovelTipo;
        
        $this->assertFalse($imovel->save());

        $imovel->municipio_id = 1;
        $imovel->nome = 'Residencial';
        $imovel->inserido_por = 1;
        
        $this->assertFalse($imovel->save());
        
        $imovel->nome = 'Residencialo';
        
        $this->assertTrue($imovel->save());
        
        unset($imovel);
        
        $imovel = new ImovelTipo;
        
        $this->assertFalse($imovel->save());
        
        $imovel->municipio_id = 2;
        $imovel->nome = 'Residencial';
        $imovel->inserido_por = 1;
        
        $this->assertTrue($imovel->save());
    }
    
    public function testUpdate()
    {
        $imovel = ImovelTipo::find(1);
        
        $this->assertInstanceOf('app\models\ImovelTipo', $imovel);

        $this->assertFalse($imovel->save());
        
        $imovel->atualizado_por = 1;
        
        $imovel->nome = 'Comercial';
        
        $this->assertFalse($imovel->save());
        
        $imovel->nome = 'Residencial';
        
        $this->assertTrue($imovel->save());
        
        $imovel->nome = null;
        $this->assertFalse($imovel->save());
        
        $imovel->nome = 'Residencial';
        $this->assertTrue($imovel->save());
    }
    
    public function testDelete()
    {
     
        $imovel = ImovelTipo::find(4);
        
        $this->assertInstanceOf('app\models\ImovelTipo', $imovel);
        
        $this->assertFalse($imovel->delete());
        
        $imovel->excluido_por = 1;
        
        $this->assertTrue($imovel->delete());
        
        $imovel = ImovelTipo::find(4);
        
        $this->assertTrue($imovel->excluido);
        $this->assertNotNull($imovel->excluido_por);
        $this->assertNotNull($imovel->data_exclusao);
        
        $this->assertEquals(6, ImovelTipo::find()->ativo()->count());
        $this->assertEquals(2, ImovelTipo::find()->excluido()->count());
    }
}
