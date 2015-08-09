<?php

namespace tests\unit\models;

use app\models\OcorrenciaTipoLocalizacao;
use Phactory;
use perspectiva\phactory\Test;
use yii\db\Expression;

class OcorrenciaTipoLocalizacaoTest extends Test
{
    public function testGetDescricao()
    {
        $this->assertEquals('Interior', OcorrenciaTipoLocalizacao::getDescricao(OcorrenciaTipoLocalizacao::INTERIOR));
        $this->assertEquals('Exterior', OcorrenciaTipoLocalizacao::getDescricao(OcorrenciaTipoLocalizacao::EXTERIOR));
        $this->assertNull(OcorrenciaTipoLocalizacao::getDescricao(9999));
    }

    public function testGetIds()
    {
        $this->assertEquals([1,2], OcorrenciaTipoLocalizacao::getIds());
    }
}
