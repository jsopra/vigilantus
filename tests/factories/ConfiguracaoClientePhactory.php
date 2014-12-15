<?php
use app\models\ConfiguracaoTipo;

class ConfiguracaoClientePhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'cliente_id' => Phactory::hasOne('cliente'),
            'valor' => 'Descrição de configuração #{sn}',
            'configuracao_id' => Phactory::hasOne('configuracao'),
        ];
    }
}
