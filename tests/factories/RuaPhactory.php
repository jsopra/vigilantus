<?php
class RuaPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'nome' => 'Rua #{sn}',
            'municipio_id' => Phactory::hasOne('municipio'),
        ];
    }
}