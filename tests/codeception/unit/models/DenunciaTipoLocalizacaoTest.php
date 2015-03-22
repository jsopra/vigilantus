<?php

namespace tests\unit\models;

use app\models\DenunciaTipoLocalizacao;
use Phactory;
use fidelize\phactory\Test;
use yii\db\Expression;

class DenunciaTipoLocalizacaoTest extends Test
{
    public function testGetDescricao()
    {
        $this->assertEquals('Interior', DenunciaTipoLocalizacao::getDescricao(DenunciaTipoLocalizacao::INTERIOR));
        $this->assertEquals('Exterior', DenunciaTipoLocalizacao::getDescricao(DenunciaTipoLocalizacao::EXTERIOR));
        $this->assertNull(DenunciaTipoLocalizacao::getDescricao(9999));
    }

    public function testGetIds()
    {
        $this->assertEquals([1,2], DenunciaTipoLocalizacao::getIds());
    }
}
