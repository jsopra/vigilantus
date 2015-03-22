<?php

namespace tests\factories;
use Phactory;

class EspecieTransmissorPhactory
{
    public function blueprint()
    {
        return [
            'nome' => 'Aedes_#{sn}',
            'cliente' => Phactory::hasOne('cliente'),
            'qtde_metros_area_foco' => 300,
            'qtde_dias_permanencia_foco' => 360
        ];
    }
}
