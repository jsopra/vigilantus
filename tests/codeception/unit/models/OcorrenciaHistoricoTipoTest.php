<?php

namespace tests\unit\models;

use app\models\OcorrenciaHistoricoTipo;
use Phactory;
use perspectiva\phactory\Test;
use yii\db\Expression;

class OcorrenciaHistoricoTipoTest extends Test
{
    public function testGetDescricao()
    {
        $this->assertEquals('Inclusão', OcorrenciaHistoricoTipo::getDescricao(OcorrenciaHistoricoTipo::INCLUSAO));
        $this->assertEquals('Aprovação', OcorrenciaHistoricoTipo::getDescricao(OcorrenciaHistoricoTipo::APROVACAO));
        $this->assertEquals('Reprovação', OcorrenciaHistoricoTipo::getDescricao(OcorrenciaHistoricoTipo::REPROVACAO));
        $this->assertNull(OcorrenciaHistoricoTipo::getDescricao(9999));
    }

    public function testGetIds()
    {
        $this->assertEquals([1,2,3], OcorrenciaHistoricoTipo::getIds());
    }
}
