<?php
class EspecieTransmissorPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'nome' => 'Aedes_#{sn}',
            'municipio_id' => Phactory::hasOne('municipio'),
            'qtde_metros_area_foco' => 300,
            'qtde_dias_permanencia_foco' => 360
        ];
    }
}