<?php

class EspecieTransmissorDoencaPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'cliente_id' => Phactory::hasOne('cliente'),
            'doenca_id' => Phactory::hasOne('doenca'),
            'especie_transmissor_id' => Phactory::hasOne('especieTransmissor'),
        ];
    }
}
