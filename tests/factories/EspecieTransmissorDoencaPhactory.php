<?php

namespace tests\factories;
use Phactory;

class EspecieTransmissorDoencaPhactory
{
    public function blueprint()
    {
        return [
            'cliente' => Phactory::hasOne('cliente'),
            'doenca' => Phactory::hasOne('doenca'),
            'especieTransmissor' => Phactory::hasOne('especieTransmissor'),
        ];
    }
}
