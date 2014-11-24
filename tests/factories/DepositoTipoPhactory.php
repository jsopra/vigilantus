<?php
class DepositoTipoPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'descricao' => 'Tipo_Depósito_#{sn}',
            'sigla' => 'TD#{sn}',
            'cliente_id' => Phactory::hasOne('cliente'),
            'inserido_por' => Phactory::hasOne('usuario'),
            'atualizado_por' => Phactory::hasOne('usuario'),
        ];
    }
}