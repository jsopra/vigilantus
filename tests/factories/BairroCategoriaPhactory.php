<?php
class BairroCategoriaPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'nome' => 'Categoria de bairro #{sn}',
            'cliente_id' => Phactory::hasOne('cliente'),
            'inserido_por' => Phactory::hasOne('usuario'),
        ];
    }
}