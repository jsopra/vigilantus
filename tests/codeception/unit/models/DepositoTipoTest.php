<?php

namespace tests\unit\models;

use Phactory;
use app\models\DepositoTipo;
use app\models\Municipio;
use fidelize\phactory\ActiveRecordTest;
use yii\db\Expression;

class DepositoTipoTest extends ActiveRecordTest
{
	public function testNaoSalvaDuplicado()
    {
        $cliente = Phactory::cliente();

        // Trava no mesmo município
        Phactory::depositoTipo(['sigla' => 'AE', 'cliente' => $cliente]);
        $tipoDepositoDuplicado = Phactory::depositoTipo(['cliente' => $cliente]);
        $tipoDepositoDuplicado->sigla = 'AE';
        $this->assertFalse($tipoDepositoDuplicado->save());

        // Permite com municípios diferentes
        $tipoDepositoDuplicado->cliente_id = Phactory::cliente()->id;
        $saved = $tipoDepositoDuplicado->save();
        $this->assertTrue($saved);
    }

    public function testSaveDepositoPai()
    {
        $cliente = Phactory::cliente();

        $tipoDeposito = Phactory::depositoTipo(['sigla' => 'AE', 'cliente' => $cliente]);

        $tipoDepositoFilho = Phactory::depositoTipo(['cliente' => $cliente]);
        $tipoDepositoFilho->sigla = 'AF';
        $tipoDepositoFilho->deposito_tipo_pai = $tipoDeposito->id;
        $this->assertTrue($tipoDepositoFilho->save());
    }

    public function testScopeDepositoSigla()
    {
        $cliente = Phactory::cliente();

        Phactory::depositoTipo(['sigla' => 'AE', 'cliente' => $cliente]);
        Phactory::depositoTipo(['sigla' => 'AG', 'cliente' => $cliente]);

        $this->assertInstanceOf("app\models\DepositoTipo", DepositoTipo::find()->daSigla('AE')->one());
        $this->assertNull(DepositoTipo::find()->daSigla('AF')->one());
        $this->assertInstanceOf("app\models\DepositoTipo", DepositoTipo::find()->daSigla('AG')->one());
    }
}
