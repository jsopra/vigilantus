<?php
class ImovelTipoPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'nome' => 'TipoImovel_#{sn}',
            'municipio_id' => Phactory::hasOne('municipio'),
            'inserido_por' => Phactory::hasOne('usuario'),
            'atualizado_por' => Phactory::hasOne('usuario'),
        ];
    }
}
