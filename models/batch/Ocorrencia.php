<?php

namespace app\models\batch;

use app\batch\Model;
use app\models\Ocorrencia;
use app\models\OcorrenciaHistorico;
use app\models\OcorrenciaHistoricoTipo;
use app\models\OcorrenciaStatus;

class Ocorrencia extends Model
{
    /**
     * @inheritdoc
     */
    public function columnLabels()
    {
        return [
            'numero_controle' => 'Número de Controle',
            'data_abertura' => 'Data da Abertura',
            'agente' => 'Agente Designado',
            'data_averiguacao' => 'Data da Averiguação',
            'acao_tomada' => 'Ação tomada',
            'classificacao' => 'Classificação',
            'bairro' => 'Bairro',
            'rua' => 'Rua',
            'numero' => 'Número',
        ];
    }

    /**
     * @inheritdoc
     */
    public function columnHints()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function insert($row, $userId = null, $clienteId = null)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        $bairro = $row->getValue('bairro') != '' ? Bairro::find()->doNome($row->getValue('bairro'))->one() : null;
        $numero = $row->getValue('numero');
        $bairroQuarteirao = $bairro && $numero ? BairroQuarteirao::find()->doBairro($bairro->id)->doNumero($numero)->one() : null;

        if($row->getValue('numero_controle') != '') {
            $ocorrencia = Ocorrencia::find()->doNumeroControle($row->getValue('numero_controle'))->one();
            if($ocorrencia) {
                $row->addError('Número de controle já existe');
                $transaction->rollback();
                return false;
            }
        }

        $ocorrencia = new Ocorrencia;
        $ocorrencia->data_criacao = $row->getValue('data_abertura');
        $ocorrencia->bairro_id = $bairro ? $bairro->id : null;
        $ocorrencia->endereco = $row->getValue('rua') . ($row->getValue('numero') != '' ? ', nº ' . $row->getValue('numero') : '') . ($row->getValue('bairro') != '' ? ' - Bairro ' . $row->getValue('bairro') : '');
        $ocorrencia->tipo_imovel = null;
        $ocorrencia->status
        $ocorrencia->ocorrencia_tipo_problema_id
        $ocorrencia->bairro_quarteirao_id = $bairroQuarteirao ? $bairroQuarteirao->id : null;
        $ocorrencia->data_fechamento
        $ocorrencia->numero_controle = $row->getValue('numero_controle');

        //se motivo é finalizador, então data averiguacao é data fechamento

        //registro historico de visitacao quando agente e data_averiguacao


            'agente' => 'Agente Designado',
            'data_averiguacao' => 'Data da Averiguação',
            'acao_tomada' => 'Ação tomada', //solucionado, extraviado (status)
            'classificacao' => 'Classificação', //pneu, caixa de agua, ... (tipo problema)

        $transaction->rollback();
        return false;

        $transaction->commit();
        return true;
    }
}
