<?php

namespace tests\factories;
use Phactory;

class ClienteModuloPhactory
{
    public function blueprint()
    {
        return [
            'cliente' => Phactory::hasOne('cliente'),
            'modulo' => Phactory::hasOne('modulo'),
        ];
    }
}
