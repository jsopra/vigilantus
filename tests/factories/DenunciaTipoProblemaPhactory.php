<?php
class DenunciaTipoProblemaPhactory
{
    public function blueprint()
    {
        return [
            'id' => '#{sn}',
            'nome' => 'Tipo #{sn}',
            'ativo' => true,
            'inserido_por' => Phactory::hasOne('usuario'),
            'cliente_id' => Phactory::hasOne('cliente'),
        ];
    }
}
