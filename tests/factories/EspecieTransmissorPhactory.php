<?php
class EspecieTransmissorPhactory
{
    public function blueprint()
    {
        return [
            'nome' => 'Aedes_#{sn}',
            'cliente_id' => Phactory::hasOne('cliente'),
            'qtde_metros_area_foco' => 300,
            'qtde_dias_permanencia_foco' => 360
        ];
    }
}
