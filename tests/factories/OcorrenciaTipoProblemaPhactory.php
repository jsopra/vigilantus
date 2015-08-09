<?php

namespace tests\factories;
use Phactory;

class OcorrenciaTipoProblemaPhactory
{
    public function blueprint()
    {
        return [
            'nome' => 'Tipo #{sn}',
            'ativo' => true,
            'inseridoPor' => Phactory::hasOne('usuario'),
            'cliente' => Phactory::hasOne('cliente'),
        ];
    }
}
