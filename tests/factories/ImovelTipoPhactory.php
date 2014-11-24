<?php
class ImovelTipoPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'nome' => 'TipoImovel_#{sn}',
            'cliente_id' => Phactory::hasOne('cliente'),
            'inserido_por' => Phactory::hasOne('usuario'),
            'atualizado_por' => Phactory::hasOne('usuario'),
        ];
    }
}
