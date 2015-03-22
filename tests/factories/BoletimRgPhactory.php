<?php

namespace tests\factories;
use Phactory;

class BoletimRgPhactory
{
    public function blueprint()
    {
        return [
            'folha' => '#{sn}',
            'cliente' => Phactory::hasOne('cliente'),
            'quarteirao' => Phactory::hasOne('bairroQuarteirao'),
            'bairro' => Phactory::hasOne('bairro'),
            'inseridoPor' => Phactory::hasOne('usuario'),
            'data' => date('Y-m-d'),
        ];
    }
}
