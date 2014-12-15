<?php

class DoencaPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'cliente_id' => Phactory::hasOne('cliente'),
            'nome' => 'Doença #{sn}',
        ];
    }
}
