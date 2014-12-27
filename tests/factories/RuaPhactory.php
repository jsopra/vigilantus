<?php
class RuaPhactory
{
    public function blueprint()
    {
        return [
            'nome' => 'Rua #{sn}',
            'cliente_id' => Phactory::hasOne('cliente'),
            'municipio_id' => Phactory::hasOne('municipio'),
        ];
    }
}
