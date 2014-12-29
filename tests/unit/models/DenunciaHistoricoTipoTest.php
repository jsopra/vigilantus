<?php

namespace tests\unit\models;

use app\models\DenunciaHistoricoTipo;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class DenunciaHistoricoTipoTest extends TestCase
{
    public function testGetDescricao()
    {
        $this->assertEquals('Inclusão', DenunciaHistoricoTipo::getDescricao(DenunciaHistoricoTipo::INCLUSAO));
        $this->assertEquals('Aprovação', DenunciaHistoricoTipo::getDescricao(DenunciaHistoricoTipo::APROVACAO));
        $this->assertEquals('Reprovação', DenunciaHistoricoTipo::getDescricao(DenunciaHistoricoTipo::REPROVACAO));
        $this->assertNull(DenunciaHistoricoTipo::getDescricao(9999));
    }

    public function testGetIds()
    {
        $this->assertEquals([1,2,3], DenunciaHistoricoTipo::getIds());
    }
}
