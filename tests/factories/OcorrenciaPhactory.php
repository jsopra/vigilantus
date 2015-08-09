<?php

namespace tests\factories;

use app\models\OcorrenciaTipoImovel;
use app\models\OcorrenciaTipoLocalizacao;
use app\models\OcorrenciaStatus;
use Phactory;

class OcorrenciaPhactory
{
    public function blueprint()
    {
        return [
            'bairro' => Phactory::hasOne('bairro'),
            'cliente' => Phactory::hasOne('cliente'),
            'ocorrenciaTipoProblema' => Phactory::hasOne('ocorrenciaTipoProblema'),
            'bairroQuarteirao' => Phactory::hasOne('bairroQuarteirao'),
            'imovel' => Phactory::hasOne('imovel'),
            'nome' => 'Denunciante #{sn}',
            'telefone' => '(49) 3316 0928',
            'endereco' => 'Endereço #{sn}',
            'email' => 'denunciante@gmail.com',
            'pontos_referencia' => 'Pontos de referencia #{sn}',
            'mensagem' => 'Mensagem de denúncia',
            'tipo_imovel' => OcorrenciaTipoImovel::CASA,
            'localizacao' => OcorrenciaTipoLocalizacao::INTERIOR,
            'status' => OcorrenciaStatus::AVALIACAO,
        ];
    }
}
