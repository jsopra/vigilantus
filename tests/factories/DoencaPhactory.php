<?php

namespace tests\factories;
use Phactory;

class DoencaPhactory
{
    public function blueprint()
    {
        return [
            'cliente' => Phactory::hasOne('cliente'),
            'nome' => 'Doença #{sn}',
        ];
    }
}
