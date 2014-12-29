<?php
class ModuloPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'nome' => 'Módulo #{sn}',
            'ativo' => true,
        ];
    }
}