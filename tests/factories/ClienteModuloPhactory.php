<?php
class ClienteModuloPhactory
{
    public function blueprint()
    {
        return [
            'cliente_id' => Phactory::hasOne('cliente'),
            'modulo_id' => Phactory::hasOne('modulo'),
        ];
    }
}
