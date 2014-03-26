<?php
class BairroRuaPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'nome' => 'Rua #{sn}',
            'municipio_id' => Phactory::hasOne('municipio'),
            'bairro_id' => Phactory::hasOne('bairro'),
        ];
    }
}