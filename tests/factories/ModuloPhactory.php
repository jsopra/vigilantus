<?php

namespace tests\factories;

class ModuloPhactory
{
    public function blueprint()
    {
        return [
            'nome' => 'Módulo #{sn}',
            'ativo' => true,
        ];
    }
}
