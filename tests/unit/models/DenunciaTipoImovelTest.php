<?php

namespace tests\unit\models;

use app\models\DenunciaTipoImovel;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class DenunciaTipoImovelTest extends TestCase
{
    public function testGetDescricao()
    {
        $this->assertEquals('Casa', DenunciaTipoImovel::getDescricao(DenunciaTipoImovel::CASA));
        $this->assertEquals('Apartamento', DenunciaTipoImovel::getDescricao(DenunciaTipoImovel::APARTAMENTO));
        $this->assertEquals('Terreno', DenunciaTipoImovel::getDescricao(DenunciaTipoImovel::TERRENO));
        $this->assertEquals('Edifício Público', DenunciaTipoImovel::getDescricao(DenunciaTipoImovel::EDIFICIO_PUBLICO));
        $this->assertEquals('Edifício Comercial', DenunciaTipoImovel::getDescricao(DenunciaTipoImovel::ESPACO_COMERCIAL));
        $this->assertEquals('Jardim/Praça', DenunciaTipoImovel::getDescricao(DenunciaTipoImovel::JARDIM_PRACA));
        $this->assertEquals('Rua', DenunciaTipoImovel::getDescricao(DenunciaTipoImovel::RUA));
        $this->assertEquals('Outro', DenunciaTipoImovel::getDescricao(DenunciaTipoImovel::OUTRO));
        $this->assertNull(DenunciaTipoImovel::getDescricao(9999));
    }

    public function testGetIds()
    {
        $this->assertEquals([1,2,3,4,5,6,7,8], DenunciaTipoImovel::getIds());
    }
}
