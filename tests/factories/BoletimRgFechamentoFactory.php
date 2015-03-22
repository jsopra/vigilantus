<?php

namespace tests\factories;
use Phactory;

class BoletimRgFechamentoPhactory
{
    public function blueprint()
    {
        return [
            'cliente' => Phactory::hasOne('cliente'),
            'boletimRg' => Phactory::hasOne('boletimRg'),
            'quantidade' => 10,
            'imovelTipo' => Phactory::hasOne('imovelTipo'),
            'imovel_lira' => false,
        ];
    }
}
