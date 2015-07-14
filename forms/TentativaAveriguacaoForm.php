<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\Ocorrencia;
use app\models\OcorrenciaHistorico;
use app\models\OcorrenciaHistoricoTipo;
use app\models\Configuracao;
use app\models\OcorrenciaStatus;

class TentativaAveriguacaoForm extends Model
{
    public $cliente_id;
    public $ocorrencia_id;
    public $agente_id;
    public $data;
    public $observacoes;
    public $usuario_id;
    public $fechou_visita;
    public $status;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['ocorrencia_id', 'agente_id', 'cliente_id', 'usuario_id', 'data'], 'required'],
            [['ocorrencia_id', 'agente_id', 'cliente_id', 'usuario_id', 'status'], 'integer'],
            [['observacoes', 'fechou_visita'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'ocorrencia_id' => 'Ocorrência',
            'agente_id' => 'Agente',
            'cliente_id' => 'Cliente',
            'data' => 'Data da Averiguação',
            'observacoes' => 'Observacoes',
            'fechou_visita' => 'Fechou Visita',
            'usuario_id' => 'Usuário',
            'status' => 'Novo Status',
        ];
    }

    /**
     * @return boolean
     */
    public function save()
    {
        $transaction = Ocorrencia::getDb()->beginTransaction();

        try {

            if(!$this->validate()) {
                $transaction->rollback();
                return false;
            }

            $ocorrencia = Ocorrencia::find()->andWhere(['id' => $this->ocorrencia_id])->one();
            if(!$ocorrencia) {
                $transaction->rollback();
                return false;
            }

            $historico = new OcorrenciaHistorico;
            $historico->cliente_id = $this->cliente_id;
            $historico->ocorrencia_id = $ocorrencia->id;
            $historico->data_associada = $this->data;
            $historico->tipo = OcorrenciaHistoricoTipo::AVERIGUACAO;
            $historico->observacoes = $this->observacoes;
            $historico->usuario_id = $this->usuario_id;
            $historico->agente_id = $this->agente_id;

            $saved = $historico->save();

            if(!$saved) {
                $transaction->rollback();
                return false;
            }

            $ocorrencia->refresh();

            if(!$this->status) {
                $qtdeAveriguacoesEncerraOcorrencia = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_TENTATIVAS_VISITACAO, $this->cliente_id);
                if($ocorrencia->quantidadeAveriguacoes == $qtdeAveriguacoesEncerraOcorrencia) {

                    $ocorrencia->scenario = Ocorrencia::SCENARIO_TROCA_STATUS;
                    $ocorrencia->usuario_id = $this->usuario_id;
                    $ocorrencia->status = OcorrenciaStatus::FECHADO;
                    $saved = $ocorrencia->save();
                    $this->fechou_visita = true;
                }
            } else {
                $ocorrencia->scenario = Ocorrencia::SCENARIO_TROCA_STATUS;
                $ocorrencia->usuario_id = $this->usuario_id;
                $ocorrencia->status = $this->status;
                $saved = $ocorrencia->save();
            }

            if(!$saved) {
                return false;
            }

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
