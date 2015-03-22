<?php

namespace tests\factories;

use app\models\ConfiguracaoTipo;
use Phactory;

class ConfiguracaoClientePhactory
{
    public function blueprint()
    {
        return [
            'cliente' => Phactory::hasOne('cliente'),
            'valor' => 'Descrição de configuração #{sn}',
            'configuracao' => Phactory::hasOne('configuracao'),
        ];
    }
}
