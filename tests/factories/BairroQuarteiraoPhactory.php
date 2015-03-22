<?php

namespace tests\factories;
use Phactory;

class BairroQuarteiraoPhactory
{
    public function blueprint()
    {
        return [
            'numero_quarteirao' => '#{sn}',
            'cliente' => Phactory::hasOne('cliente'),
            'municipio' => Phactory::hasOne('municipio'),
            'bairro' => Phactory::hasOne('bairro'),
            'inseridoPor' => Phactory::hasOne('usuario'),
            'coordenadasJson' => '[{"A":"-27.106734958317","k":"-52.615531682968"},{"A":"-27.105665311467","k":"-52.614759206772"},{"A":"-27.105550705842","k":"-52.614244222641"},{"A":"-27.106524849921","k":"-52.614072561264"},{"A":"-27.106734958317","k":"-52.615531682968"}]',
            'coordenadas_area' => '0103000020E61000000100000005000000BA4F73FB521B3BC0ECFFFFBDC94E4AC08A12C1E10C1B3BC01500006EB04E4AC03EA9FD5E051B3BC00100008E9F4E4AC03C9F6A36451B3BC0FBFFFFED994E4AC0BA4F73FB521B3BC0ECFFFFBDC94E4AC0',
        ];
    }
}
