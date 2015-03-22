<?php

namespace tests\factories;
use Phactory;

class RuaPhactory
{
    public function blueprint()
    {
        return [
            'nome' => 'Rua #{sn}',
            'cliente' => Phactory::hasOne('cliente'),
            'municipio' => Phactory::hasOne('municipio'),
        ];
    }
}
