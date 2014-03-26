<?php
class BairroPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'nome' => 'Bairro #{sn}',
            'municipio_id' => Phactory::hasOne('municipio'),
            'bairro_tipo_id' => Phactory::hasOne('bairroTipo'),
        ];
    }
}