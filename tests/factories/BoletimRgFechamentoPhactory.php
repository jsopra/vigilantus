<?php

namespace tests\factories;
use Phactory;

class BoletimRgFechamentoPhactory
{
    public function blueprint()
    {
        return [
            'boletimRg' => Phactory::hasOne('boletimRg'),
            'cliente' => Phactory::hasOne('cliente'),
            'imovelTipo' => Phactory::hasOne('imovelTipo'),
            'imovel_lira' => false,
            'quantidade' => 1,
        ];
    }
}
