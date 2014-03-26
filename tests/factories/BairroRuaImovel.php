<?php
class BairroRuaImovelPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'municipio_id' => Phactory::hasOne('municipio'),
            'bairro_rua_id' => Phactory::hasOne('bairroRua'),
        ];
    }
}