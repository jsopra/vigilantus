<?php
namespace app\models;

class TermoResponsabilidadeStatus
{
    const ABERTO = 1;
    const SOLICIONADO = 2;
    const ABERTO_DENUNCIA_PARA_FISCAIS = 3;
    const PRORROGADO_PRAZO = 4;
    const INCONSISTENTE = 5;
    const FECHADO = 6;

    public static function getDescricoes()
    {
        return [
            self::ABERTO => 'Aberto',
            self::SOLICIONADO => 'Solucionado',
            self::ABERTO_DENUNCIA_PARA_FISCAIS => 'Aberto Denúncia para Fiscais',
            self::PRORROGADO_PRAZO => 'Prorrogado',
            self::INCONSISTENTE => 'Inconsistente',
            self::FECHADO => 'Fechado',
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

    public static function getStatusTerminativos()
    {
        return [
            self::SOLICIONADO,
            self::INCONSISTENTE,
            self::FECHADO,
        ];
    }

    public static function isStatusTerminativo($idStatus)
    {
        $status = self::getStatusTerminativos();
        return in_array($idStatus, $status);
    }

    public static function getStatusPossiveis($idStatus)
    {
        $status = [];

        switch($idStatus)
        {
            case self::ABERTO :
            case self::ABERTO_DENUNCIA_PARA_FISCAIS :
            case self::PRORROGADO_PRAZO :
            {
                $status = [
                    self::FECHADO => self::getDescricao(self::FECHADO),
                    self::SOLICIONADO => self::getDescricao(self::SOLICIONADO),
                    self::INCONSISTENTE => self::getDescricao(self::INCONSISTENTE),
                ];
                break;
            }

            default : {
                break;
            }
        }

        return $status;
    }
}
