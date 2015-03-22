<?php

namespace tests\factories;
use Phactory;

class BairroCategoriaPhactory
{
    public function blueprint()
    {
        return [
            'nome' => 'Categoria de bairro #{sn}',
            'cliente' => Phactory::hasOne('cliente'),
            'inseridoPor' => Phactory::hasOne('usuario'),
        ];
    }
}
