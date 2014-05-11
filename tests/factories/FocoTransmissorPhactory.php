<?php
class FocoTransmissorPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'tecnico' => 'Tecnico',
            'laboratorio' => 'Labz',
            'imovel_id' => Phactory::hasOne('imovel'),
            'quantidade_ovos' => 2,
            'quantidade_forma_adulta' => 2,
            'quantidade_forma_aquatica' => 2,
            'tipo_deposito_id' => Phactory::hasOne('depositoTipo'),
            'especie_transmissor_id' => Phactory::hasOne('especieTransmissor'),
            'data_entrada' => date('Y-m-d'),
            'data_exame' => date('Y-m-d'),
            'data_coleta' => date('Y-m-d'),
            'inserido_por' => Phactory::hasOne('usuario'),
            'atualizado_por' => Phactory::hasOne('usuario'),
        ];
    }
}