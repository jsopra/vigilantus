<?php
class ClienteModuloPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'cliente_id' => Phactory::hasOne('cliente'),
            'modulo_id' => Phactory::hasOne('modulo'),
        ];
    }
}