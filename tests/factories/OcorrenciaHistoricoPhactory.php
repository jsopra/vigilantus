<?php

namespace tests\factories;

use app\models\OcorrenciaHistoricoTipo;
use app\models\OcorrenciaStatus;
use Phactory;

class OcorrenciaHistoricoPhactory
{
    public function blueprint()
    {
        return [
            'ocorrencia' => Phactory::hasOne('ocorrencia'),
            'tipo' => OcorrenciaHistoricoTipo::INCLUSAO,
            'observacoes' => 'Observações de histórico',
            'status_antigo' => OcorrenciaStatus::AVALIACAO,
            'status_novo' => OcorrenciaStatus::APROVADA,
            'usuario' => Phactory::hasOne('usuario'),
            'cliente' => Phactory::hasOne('cliente'),
        ];
    }
}
