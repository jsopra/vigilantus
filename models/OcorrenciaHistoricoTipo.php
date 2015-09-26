<?php
namespace app\models;

class OcorrenciaHistoricoTipo
{
    const INCLUSAO = 1;
    const APROVACAO = 2;
    const REPROVACAO = 3;
    const INFORMACAO = 4;
    const AVERIGUACAO = 5;
    const ACAO_TOMADA = 6;

    public static function getDescricoes()
    {
        return [
            self::INCLUSAO => 'Inclusão',
            self::APROVACAO => 'Aprovação',
            self::REPROVACAO => 'Reprovação',
            self::INFORMACAO => 'Informação',
            self::AVERIGUACAO => 'Averiguação',
            self::ACAO_TOMADA => 'Ação tomada',
        ];
    }

    public static function getDescricao($id)
    {
        $descricoes = self::getDescricoes();
        return isset($descricoes[$id]) ? $descricoes[$id] : null;
    }

    public static function getIDs()
    {
        return array_keys(self::getDescricoes());
    }
}
