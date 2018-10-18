<?php

namespace api\forms;

use Yii;
use yii\base\Model;
use app\models\SemanaEpidemiologicaVisita;
use app\models\VisitaImovel;
use app\models\VisitaImovelDeposito;
use app\models\VisitaImovelTratamento;

class ExecucaoVisitaForm extends Model
{
    public $visita_id;
    public $visita_status_id;
    public $usuario_id;
    public $imoveis;
    public $data_atividade;

    private $visita;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['visita_id', 'usuario_id', 'visita_status_id', 'data_atividade'], 'required'],
            [['visita_id', 'usuario_id', 'visita_status_id'], 'integer'],
            [['data_atividade', 'imoveis', 'visita'], 'safe'],
        ];
    }

    public function afterValidate()
    {
        $parent = parent::afterValidate();

        if (!$this->imoveis) {
            $this->addError('imoveis', 'Ao menos um imóvel deve ser enviado para executar a visita');
        }  

        $this->visita = SemanaEpidemiologicaVisita::find()->andWhere(['id' => $this->visita_id])->one();
        if(!$this->visita) {
            $this->addError('visita_id', 'Visita não localizada');
        }

        return $parent;
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'visita_id' => 'Visita',
            'usuario_id' => 'Usuário',
            'visita_status_id' => 'Status da Visita',
            'data_atividade' => 'Data da Atividade',
            'imoveis' => 'Imóveis',
        ];
    }

    public function save()
    {
        if(!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {

            //atualiza visita
            $this->visita->visita_status_id = $this->visita_status_id;
            $this->visita->atualizado_por = $this->usuario_id;
            if (!$this->visita->save()) {
                $this->addError('visita_id', 'Erro ao atualizar status da visita');
                throw new \Exception('Erro ao atualizar status da visita');
            }

            //adiciona imóveis da visita
            foreach ($this->imoveis as $imovel)
            {
                
            }

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollback();
            return false;
        }
    }

    private function getValue($array, $index)
    {
        return isset($array[$index]) ? $array[$index] : null;
    }
}
