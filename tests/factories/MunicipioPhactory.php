<?php

namespace tests\factories;

class MunicipioPhactory
{
    public function blueprint()
    {
        return [
            'nome' => 'Município #{sn}',
            'sigla_estado' => 'SC',
            'coordenadas_area' => '0101000020E6100000EFAEB321FF183BC086AB0320EE4E4AC0',
            'brasao' => null
        ];
    }
}
