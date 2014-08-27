<?php

namespace tests\unit\models;

use Phactory;
use app\models\DepositoTipo;
use app\models\Municipio;
use tests\TestCase;
use yii\db\Expression;

class DepositoTipoTest extends TestCase
{
	public function testNaoSalvaDuplicado()
    {
        // Trava no mesmo município
        Phactory::depositoTipo(['sigla' => 'AE', 'municipio_id' => 1]);
        $tipoDepositoDuplicado = Phactory::depositoTipo(['municipio_id' => 1]);
        $tipoDepositoDuplicado->sigla = 'AE';
        $this->assertFalse($tipoDepositoDuplicado->save()); 

        // Permite com municípios diferentes
        $tipoDepositoDuplicado->municipio_id = Phactory::municipio()->id;
        $saved = $tipoDepositoDuplicado->save();
        $this->assertTrue($saved);
    }
    
    public function testSaveDepositoPai()
    {
        $tipoDeposito = Phactory::depositoTipo(['sigla' => 'AE', 'municipio_id' => 1]);
        
        $tipoDepositoFilho = Phactory::depositoTipo(['municipio_id' => 1]);
        $tipoDepositoFilho->sigla = 'AF';
        $tipoDepositoFilho->deposito_tipo_pai = $tipoDeposito->id;
        $this->assertTrue($tipoDepositoFilho->save()); 
    }
    
    public function testScopeDepositoSigla()
    {
        Phactory::depositoTipo(['sigla' => 'AE', 'municipio_id' => 1]);
        Phactory::depositoTipo(['sigla' => 'AG', 'municipio_id' => 1]);
        
        $this->assertInstanceOf("app\models\DepositoTipo", DepositoTipo::find()->daSigla('AE')->one());
        $this->assertNull(DepositoTipo::find()->daSigla('AF')->one());
        $this->assertInstanceOf("app\models\DepositoTipo", DepositoTipo::find()->daSigla('AG')->one());
    }
}   