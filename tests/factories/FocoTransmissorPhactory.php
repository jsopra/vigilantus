<?php

namespace tests\factories;
use Phactory;

class FocoTransmissorPhactory
{
    public function blueprint()
    {
        return [
            'tecnico' => 'Tecnico',
            'laboratorio' => 'Labz',
            'bairroQuarteirao' => Phactory::hasOne('bairroQuarteirao'),
            'quantidade_ovos' => 2,
            'quantidade_forma_adulta' => 2,
            'quantidade_forma_aquatica' => 2,
            'tipoDeposito' => Phactory::hasOne('depositoTipo'),
            'especieTransmissor' => Phactory::hasOne('especieTransmissor'),
            'data_entrada' => date('Y-m-d'),
            'data_exame' => date('Y-m-d'),
            'data_coleta' => date('Y-m-d'),
            'inseridoPor' => Phactory::hasOne('usuario'),
            'atualizadoPor' => Phactory::hasOne('usuario'),
            'cliente' => Phactory::hasOne('cliente'),
        ];
    }
}
