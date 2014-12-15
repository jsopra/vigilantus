<?php
use app\models\ConfiguracaoTipo;

class ConfiguracaoPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'nome' => 'Configuracao #{sn}',
            'descricao' => 'Descrição de configuração #{sn}',
            'tipo' => ConfiguracaoTipo::TIPO_STRING,
            'valor' => 'teste',
            'valores_possiveis'  => null
        ];
    }

    public function inteiro()
    {
        return array(
            'tipo' => ConfiguracaoTipo::TIPO_INTEIRO,
            'valor' => '10',
        );
    }

    public function decimal()
    {
        return array(
            'tipo' => ConfiguracaoTipo::TIPO_DECIMAL,
            'valor' => '10.25',
        );
    }

    public function boleano()
    {
        return array(
            'tipo' => ConfiguracaoTipo::TIPO_BOLEANO,
            'valor' => '0',
        );
    }

    public function range()
    {
        return array(
            'tipo' => ConfiguracaoTipo::TIPO_RANGE,
            'valor' => '1',
            'valores_possiveis' => serialize(['1' => 'a', '2' => 'b']),
        );
    }

    public function time()
    {
        return array(
            'tipo' => ConfiguracaoTipo::TIPO_TIME,
            'valor' => '10:00:00',
        );
    }
}
