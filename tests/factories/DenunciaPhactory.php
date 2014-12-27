<?php
use app\models\DenunciaTipoImovel;
use app\models\DenunciaTipoLocalizacao;
use app\models\DenunciaStatus;

class DenunciaPhactory
{
    public function blueprint()
    {
        return [
            'nome' => 'Denunciante #{sn}',
            'telefone' => '(49) 3316 0928',
            'bairro_id' => Phactory::hasOne('bairro'),
            'imovel_id' => null,
            'endereco' => 'Endereço #{sn}',
            'cliente_id' => Phactory::hasOne('cliente'),
            'email' => 'denunciante@gmail.com',
            'pontos_referencia' => 'Pontos de referencia #{sn}',
            'mensagem' => 'Mensagem de denúncia',
            'tipo_imovel' => DenunciaTipoImovel::CASA,
            'localizacao' => DenunciaTipoLocalizacao::INTERIOR,
            'status' => DenunciaStatus::AVALIACAO,
            'denuncia_tipo_problema_id' => Phactory::hasOne('denunciaTipoProblema'),
            'bairro_quarteirao_id' => Phactory::hasOne('bairroQuarteirao'),
        ];
    }
}
