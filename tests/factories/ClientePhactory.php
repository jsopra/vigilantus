<?php
class ClientePhactory
{
    public function blueprint()
    {
        return [
            'municipio_id' => Phactory::hasOne('municipio'),
            'nome_contato' => 'Contato #{sn}',
            'email_contato' => 'cliente@vigilantus.com.br',
            'telefone_contato' => '(49) 0000-0000',
            'departamento' => 'Departamento Federal',
            'cargo' => 'Diretor',
            'rotulo' => null
        ];
    }
}
