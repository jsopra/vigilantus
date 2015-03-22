<?php

namespace tests\factories;
use Phactory;

class ImovelPhactory
{
    public function blueprint()
    {
        return [
            'cliente' => Phactory::hasOne('cliente'),
            'municipio' => Phactory::hasOne('municipio'),
            'rua' => Phactory::hasOne('rua'),
            'bairroQuarteirao' => Phactory::hasOne('bairroQuarteirao'),
            'imovel_tipo_id' => 1, //Phactory::hasOne('imovelTipo'),
            'imovel_lira' => false,
        ];
    }
}
