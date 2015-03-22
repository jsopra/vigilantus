<?php

namespace tests\factories;

use app\models\DenunciaHistoricoTipo;
use app\models\DenunciaStatus;
use Phactory;

class DenunciaHistoricoPhactory
{
    public function blueprint()
    {
        return [
            'denuncia' => Phactory::hasOne('denuncia'),
            'tipo' => DenunciaHistoricoTipo::INCLUSAO,
            'observacoes' => 'Observações de histórico',
            'status_antigo' => DenunciaStatus::AVALIACAO,
            'status_novo' => DenunciaStatus::APROVADA,
            'usuario' => Phactory::hasOne('usuario'),
            'cliente' => Phactory::hasOne('cliente'),
        ];
    }
}
