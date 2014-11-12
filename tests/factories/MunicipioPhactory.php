<?php
class MunicipioPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'nome' => 'Município #{sn}',
            'sigla_estado' => 'SC',
            'nome_contato' => 'Contato #{sn}',
            'telefone_contato' => '(49) 0000-0000',
            'departamento' => 'Departamento Federal',
        ];
    }
}