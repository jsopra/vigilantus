<?php

namespace tests\factories;
use Phactory;

class ImovelTipoPhactory
{
    public function blueprint()
    {
        return [
            'nome' => 'TipoImovel_#{sn}',
            'cliente' => Phactory::hasOne('cliente'),
            'inseridoPor' => Phactory::hasOne('usuario'),
            'atualizadoPor' => Phactory::hasOne('usuario'),
        ];
    }
}
