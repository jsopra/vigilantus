<?php

namespace tests\unit\models;

use app\models\OcorrenciaTipoImovel;
use Phactory;
use fidelize\phactory\Test;
use yii\db\Expression;

class OcorrenciaTipoImovelTest extends Test
{
    public function testGetDescricao()
    {
        $this->assertEquals('Casa', OcorrenciaTipoImovel::getDescricao(OcorrenciaTipoImovel::CASA));
        $this->assertEquals('Apartamento', OcorrenciaTipoImovel::getDescricao(OcorrenciaTipoImovel::APARTAMENTO));
        $this->assertEquals('Terreno', OcorrenciaTipoImovel::getDescricao(OcorrenciaTipoImovel::TERRENO));
        $this->assertEquals('Edifício Público', OcorrenciaTipoImovel::getDescricao(OcorrenciaTipoImovel::EDIFICIO_PUBLICO));
        $this->assertEquals('Edifício Comercial', OcorrenciaTipoImovel::getDescricao(OcorrenciaTipoImovel::ESPACO_COMERCIAL));
        $this->assertEquals('Jardim/Praça', OcorrenciaTipoImovel::getDescricao(OcorrenciaTipoImovel::JARDIM_PRACA));
        $this->assertEquals('Rua', OcorrenciaTipoImovel::getDescricao(OcorrenciaTipoImovel::RUA));
        $this->assertEquals('Outro', OcorrenciaTipoImovel::getDescricao(OcorrenciaTipoImovel::OUTRO));
        $this->assertNull(OcorrenciaTipoImovel::getDescricao(9999));
    }

    public function testGetIds()
    {
        $this->assertEquals([1,2,3,4,5,6,7,8], OcorrenciaTipoImovel::getIds());
    }
}
