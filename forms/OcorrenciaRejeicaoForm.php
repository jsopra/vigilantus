<?php
namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\Ocorrencia;
use app\models\OcorrenciaStatus;

class OcorrenciaRejeicaoForm extends Model
{
    public $ocorrencia_id;
    public $usuario_id;
    public $observacoes;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['ocorrencia_id', 'usuario_id', 'observacoes'], 'required'],
            [['ocorrencia_id', 'usuario_id'], 'integer'],
            [['observacoes'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'ocorrencia_id' => 'Ocorrência',
            'usuario_id' => 'Usuário',
            'observacoes' => 'Observações',
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

            $ocorrencia->scenario = Ocorrencia::SCENARIO_TROCA_STATUS;
            $ocorrencia->status = OcorrenciaStatus::REPROVADA;
            $ocorrencia->usuario_id = $this->usuario_id;
            $ocorrencia->observacoes = $this->observacoes;
            $saved = $ocorrencia->save();

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
