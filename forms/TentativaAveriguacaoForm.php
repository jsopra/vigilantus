<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\Denuncia;
use app\models\DenunciaHistorico;
use app\models\DenunciaHistoricoTipo;
use app\models\Configuracao;
use app\models\DenunciaStatus;

class TentativaAveriguacaoForm extends Model
{
    public $cliente_id;
    public $denuncia_id;
    public $agente_id;
    public $data;
    public $observacoes;
    public $usuario_id;
    public $fechou_visita;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['denuncia_id', 'agente_id', 'cliente_id', 'usuario_id', 'data'], 'required'],
            [['denuncia_id', 'agente_id', 'cliente_id', 'usuario_id'], 'integer'],
            [['observacoes', 'fechou_visita'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'denuncia_id' => 'Denúncia',
            'agente_id' => 'Agente',
            'cliente_id' => 'Cliente',
            'data' => 'Data da Averiguação',
            'observacoes' => 'Observacoes',
            'fechou_visita' => 'Fechou Visita',
            'usuario_id' => 'Usuário',
        ];
    }

    /**
     * @return boolean
     */
    public function save()
    {
        $transaction = Denuncia::getDb()->beginTransaction();

        try {

            if(!$this->validate()) {
                $transaction->rollback();
                return false;
            }

            $denuncia = Denuncia::find()->andWhere(['id' => $this->denuncia_id])->one();
            if(!$denuncia) {
                $transaction->rollback();
                return false;
            }

            $historico = new DenunciaHistorico;
            $historico->cliente_id = $this->cliente_id;
            $historico->denuncia_id = $denuncia->id;
            $historico->data_associada = $this->data;
            $historico->tipo = DenunciaHistoricoTipo::AVERIGUACAO;
            $historico->observacoes = $this->observacoes;
            $historico->usuario_id = $this->usuario_id;
            $historico->agente_id = $this->agente_id;

            $saved = $historico->save();

            if(!$saved) {
                $transaction->rollback();
                return false;
            }

            $denuncia->refresh();

            $qtdeAveriguacoesEncerraDenuncia = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_TENTATIVAS_VISITACAO, $this->cliente_id);

            if($denuncia->quantidadeAveriguacoes == $qtdeAveriguacoesEncerraDenuncia) {

                $denuncia->scenario = 'trocaStatus';
                $denuncia->usuario_id = $this->usuario_id;
                $denuncia->status = DenunciaStatus::FECHADO;

                $saved = $denuncia->save();

                $this->fechou_visita = true;
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
