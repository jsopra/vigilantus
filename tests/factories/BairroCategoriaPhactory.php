<?php
class BairroCategoriaPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'nome' => 'Categoria de bairro #{sn}',
            'municipio_id' => Phactory::hasOne('municipio'),
            'inserido_por' => Phactory::hasOne('usuario'),
        ];
    }
}