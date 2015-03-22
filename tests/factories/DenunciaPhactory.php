<?php

namespace tests\factories;

use app\models\DenunciaTipoImovel;
use app\models\DenunciaTipoLocalizacao;
use app\models\DenunciaStatus;
use Phactory;

class DenunciaPhactory
{
    public function blueprint()
    {
        return [
            'bairro' => Phactory::hasOne('bairro'),
            'cliente' => Phactory::hasOne('cliente'),
            'denunciaTipoProblema' => Phactory::hasOne('denunciaTipoProblema'),
            'bairroQuarteirao' => Phactory::hasOne('bairroQuarteirao'),
            'imovel' => Phactory::hasOne('imovel'),
            'nome' => 'Denunciante #{sn}',
            'telefone' => '(49) 3316 0928',
            'endereco' => 'Endereço #{sn}',
            'email' => 'denunciante@gmail.com',
            'pontos_referencia' => 'Pontos de referencia #{sn}',
            'mensagem' => 'Mensagem de denúncia',
            'tipo_imovel' => DenunciaTipoImovel::CASA,
            'localizacao' => DenunciaTipoLocalizacao::INTERIOR,
            'status' => DenunciaStatus::AVALIACAO,
        ];
    }
}
