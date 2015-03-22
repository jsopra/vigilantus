<?php

namespace tests\factories;
use Phactory;

class DepositoTipoPhactory
{
    public function blueprint()
    {
        return [
            'descricao' => 'Tipo_Depósito_#{sn}',
            'sigla' => 'TD#{sn}',
            'cliente' => Phactory::hasOne('cliente'),
            'inseridoPor' => Phactory::hasOne('usuario'),
            'atualizadoPor' => Phactory::hasOne('usuario'),
        ];
    }
}
