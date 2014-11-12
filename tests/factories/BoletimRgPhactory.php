<?php
class BoletimRgPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'folha' => '#{sn}',
            'municipio_id' => Phactory::hasOne('municipio'),
            'bairro_quarteirao_id' => Phactory::hasOne('bairroQuarteirao'),
            'bairro_id' => Phactory::hasOne('bairro'),
            'inserido_por' => Phactory::hasOne('usuario'),
            'data' => date('d/m/Y'),
        ];
    }
}
