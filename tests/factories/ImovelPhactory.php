<?php
class ImovelPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'municipio_id' => Phactory::hasOne('municipio'),
            'rua_id' => Phactory::hasOne('rua'),
            'bairro_quarteirao_id' => Phactory::hasOne('bairroQuarteirao'),
            'imovel_tipo_id' => 1, //Phactory::hasOne('imovelTipo'),
        ];
    }
}