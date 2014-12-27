<?php
namespace app\models;

class ConfiguracaoTipo
{
    const TIPO_STRING = 'STRING';
    const TIPO_INTEIRO = 'INTEIRO';
    const TIPO_DECIMAL = 'DECIMAL';
    const TIPO_BOLEANO = 'BOLEANO';
    const TIPO_RANGE = 'RANGE';
    const TIPO_TIME = 'TIME';

    /**
     * Tipos de configuração posíveis
     * @return array
     */
    public static function getTiposDeConfiguracao()
    {
        return [
            self::TIPO_STRING,
            self::TIPO_INTEIRO,
            self::TIPO_DECIMAL,
            self::TIPO_BOLEANO,
            self::TIPO_RANGE,
            self::TIPO_TIME,
        ];
    }
}
