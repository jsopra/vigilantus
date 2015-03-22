<?php

namespace tests\factories;
use Phactory;

class BoletimRgImovelPhactory
{
    public function blueprint()
    {
        return [
            'cliente' => Phactory::hasOne('cliente'),
            'boletimRg' => Phactory::hasOne('boletimRg'),
            'imovel' => Phactory::hasOne('imovel'),
            'imovel_tipo_id' => 1, //Phactory::hasOne('imovelTipo'),
            'rua' => Phactory::hasOne('rua'),
            'rua_nome' => 'Rua #{sn}',
            'imovel_numero' => '12#{sn}',
            'imovel_complemento' => 'D',
            'imovel_lira' => false,
        ];
    }
}
