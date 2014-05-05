<?php

class BoletimRgImovelPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'municipio_id' => Phactory::hasOne('municipio'),
            'boletim_rg_id' => Phactory::hasOne('boletimRg'),
            'imovel_id' => Phactory::hasOne('imovel'),
            'imovel_tipo_id' => 1, //Phactory::hasOne('imovelTipo'),
            'rua_id' => Phactory::hasOne('rua'),
            'rua_nome' => 'Rua #{sn}',
            'imovel_numero' => '12#{sn}',
            'imovel_complemento' => 'D',
            'imovel_lira' => false,
        ];
    }
}
