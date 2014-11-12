<?php
class DepositoTipoPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'descricao' => 'Tipo_Depósito_#{sn}',
            'sigla' => 'TD#{sn}',
            'municipio_id' => Phactory::hasOne('municipio'),
            'inserido_por' => Phactory::hasOne('usuario'),
            'atualizado_por' => Phactory::hasOne('usuario'),
        ];
    }
}