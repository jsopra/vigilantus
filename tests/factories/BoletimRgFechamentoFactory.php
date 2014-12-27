<?php

class BoletimRgFechamentoPhactory
{
    public function blueprint()
    {
        return [
            'cliente_id' => Phactory::hasOne('cliente'),
            'boletim_rg_id' => Phactory::hasOne('boletimRg'),
            'quantidade' => 10,
            'imovel_tipo_id' => Phactory::hasOne('imovelTipo'),
            'imovel_lira' => false,
        ];
    }
}
