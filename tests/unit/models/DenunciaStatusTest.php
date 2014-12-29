<?php

namespace tests\unit\models;

use app\models\DenunciaStatus;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class DenunciaStatusTest extends TestCase
{
    public function testGetDescricao()
    {
        $this->assertEquals('Em Avaliação', DenunciaStatus::getDescricao(DenunciaStatus::AVALIACAO));
        $this->assertEquals('Aprovada', DenunciaStatus::getDescricao(DenunciaStatus::APROVADA));
        $this->assertEquals('Não Procedente', DenunciaStatus::getDescricao(DenunciaStatus::NAO_PROCEDENTE));
        $this->assertEquals('Extraviada', DenunciaStatus::getDescricao(DenunciaStatus::EXTREVIADA));
        $this->assertEquals('Não encontrado', DenunciaStatus::getDescricao(DenunciaStatus::NAO_ENCONTRADO));
        $this->assertEquals('Solucionado', DenunciaStatus::getDescricao(DenunciaStatus::SOLICIONADO));
        $this->assertEquals('Aberto TR', DenunciaStatus::getDescricao(DenunciaStatus::ABERTO_TERMO_RESPONSABILIDADE));
        $this->assertEquals('Enc. para fiscalização urbana', DenunciaStatus::getDescricao(DenunciaStatus::ENCAMINHADO_FISCALIZACAO_URBANA));
        $this->assertEquals('Enc. para fiscalização sanitária', DenunciaStatus::getDescricao(DenunciaStatus::ENCAMINHADO_FISCALIZACAO_SANITARIA));
        $this->assertEquals('Fechado', DenunciaStatus::getDescricao(DenunciaStatus::FECHADO));
        $this->assertEquals('Auto de intimação', DenunciaStatus::getDescricao(DenunciaStatus::AUTO_INTIMACAO));
        $this->assertEquals('Enc. para Serv. Urb.', DenunciaStatus::getDescricao(DenunciaStatus::ENCAMINHADO_PARA_SERVICO_URBANO));
        $this->assertEquals('Reprovada', DenunciaStatus::getDescricao(DenunciaStatus::REPROVADA));

        $this->assertNull(DenunciaStatus::getDescricao(9999));
    }

    public function testGetIds()
    {
        $this->assertEquals([1,2,3,4,5,6,7,8,9,10,11,12,13], DenunciaStatus::getIds());
    }

    public function testStatusTerminativo()
    {
        $this->assertTrue(DenunciaStatus::isStatusTerminativo(DenunciaStatus::REPROVADA));

        $this->assertFalse(DenunciaStatus::isStatusTerminativo(DenunciaStatus::AVALIACAO));
    }

    public function testGetStatusPossiveisAvaliacao()
    {
        $this->assertEquals([], DenunciaStatus::getStatusPossiveis(DenunciaStatus::AVALIACAO));
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

        $this->assertEquals($esperado, DenunciaStatus::getStatusPossiveis(DenunciaStatus::APROVADA));
    }
}
