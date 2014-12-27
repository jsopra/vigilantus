<?php
use app\models\DenunciaHistoricoTipo;
use app\models\DenunciaStatus;

class DenunciaHistoricoPhactory
{
    public function blueprint()
    {
        return [
            'denuncia_id' => Phactory::hasOne('denuncia'),
            'tipo' => DenunciaHistoricoTipo::INCLUSAO,
            'observacoes' => 'Observações de histórico',
            'status_antigo' => DenunciaStatus::AVALIACAO,
            'status_novo' => DenunciaStatus::APROVADA,
            'usuario_id' => Phactory::hasOne('usuario'),
            'cliente_id' => Phactory::hasOne('cliente'),
        ];
    }
}
