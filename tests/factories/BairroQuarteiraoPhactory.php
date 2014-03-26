<?php
class BairroQuarteiraoPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'numero_quarteirao' => '#{sn}',
            'municipio_id' => Phactory::hasOne('municipio'),
            'bairro_id' => Phactory::hasOne('bairro'),
            'inserido_por' => Phactory::hasOne('usuario'),
        ];
    }
}