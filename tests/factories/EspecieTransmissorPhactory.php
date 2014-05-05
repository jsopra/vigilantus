<?php
class EspecieTransmissorPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'nome' => 'Aedes_#{sn}',
            'municipio_id' => Phactory::hasOne('municipio'),
        ];
    }
}