<?php
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
