<?php

namespace tests\unit\models;

use app\models\OcorrenciaStatus;
use Phactory;
use fidelize\phactory\Test;
use yii\db\Expression;

class OcorrenciaStatusTest extends Test
{
    public function testGetDescricao()
    {
        $this->assertEquals('Em Avaliação', OcorrenciaStatus::getDescricao(OcorrenciaStatus::AVALIACAO));
        $this->assertEquals('Aprovada', OcorrenciaStatus::getDescricao(OcorrenciaStatus::APROVADA));
        $this->assertEquals('Não Procedente', OcorrenciaStatus::getDescricao(OcorrenciaStatus::NAO_PROCEDENTE));
        $this->assertEquals('Extraviada', OcorrenciaStatus::getDescricao(OcorrenciaStatus::EXTREVIADA));
        $this->assertEquals('Não encontrado', OcorrenciaStatus::getDescricao(OcorrenciaStatus::NAO_ENCONTRADO));
        $this->assertEquals('Solucionado', OcorrenciaStatus::getDescricao(OcorrenciaStatus::SOLICIONADO));
        $this->assertEquals('Aberto TR', OcorrenciaStatus::getDescricao(OcorrenciaStatus::ABERTO_TERMO_RESPONSABILIDADE));
        $this->assertEquals('Enc. para fiscalização urbana', OcorrenciaStatus::getDescricao(OcorrenciaStatus::ENCAMINHADO_FISCALIZACAO_URBANA));
        $this->assertEquals('Enc. para fiscalização sanitária', OcorrenciaStatus::getDescricao(OcorrenciaStatus::ENCAMINHADO_FISCALIZACAO_SANITARIA));
        $this->assertEquals('Fechado', OcorrenciaStatus::getDescricao(OcorrenciaStatus::FECHADO));
        $this->assertEquals('Auto de intimação', OcorrenciaStatus::getDescricao(OcorrenciaStatus::AUTO_INTIMACAO));
        $this->assertEquals('Enc. para Serv. Urb.', OcorrenciaStatus::getDescricao(OcorrenciaStatus::ENCAMINHADO_PARA_SERVICO_URBANO));
        $this->assertEquals('Reprovada', OcorrenciaStatus::getDescricao(OcorrenciaStatus::REPROVADA));

        $this->assertNull(OcorrenciaStatus::getDescricao(9999));
    }

    public function testGetIds()
    {
        $this->assertEquals([1,2,3,4,5,6,7,8,9,10,11,12,13], OcorrenciaStatus::getIds());
    }

    public function testStatusTerminativo()
    {
        $this->assertTrue(OcorrenciaStatus::isStatusTerminativo(OcorrenciaStatus::REPROVADA));

        $this->assertFalse(OcorrenciaStatus::isStatusTerminativo(OcorrenciaStatus::AVALIACAO));
    }

    public function testGetStatusPossiveisAvaliacao()
    {
        $this->assertEquals([], OcorrenciaStatus::getStatusPossiveis(OcorrenciaStatus::AVALIACAO));
    }

    public function testGetStatusPossiveisAndamento()
    {
        $esperado = [
            4 => 'Extraviada',
            5 => 'Não encontrado',
            6 => 'Solucionado',
            7 => 'Aberto TR',
            8 => 'Enc. para fiscalização urbana',
            9 => 'Enc. para fiscalização sanitária',
            11 => 'Auto de intimação',
            12 => 'Enc. para Serv. Urb.',
            10 => 'Fechado',
        ];

        $this->assertEquals($esperado, OcorrenciaStatus::getStatusPossiveis(OcorrenciaStatus::APROVADA));
    }
}
