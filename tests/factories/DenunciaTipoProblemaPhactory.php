<?php
class DenunciaTipoProblemaPhactory
{
    public function blueprint()
    {
        return [
            'nome' => 'Tipo #{sn}',
            'ativo' => true,
            'inserido_por' => Phactory::hasOne('usuario'),
            'cliente_id' => Phactory::hasOne('cliente'),
        ];
    }
}
