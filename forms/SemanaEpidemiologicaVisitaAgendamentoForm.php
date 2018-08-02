<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\SemanaEpidemiologicaVisita;

class SemanaEpidemiologicaVisitaAgendamentoForm extends Model
{
    public $semana_epidemiologica_id;
    public $agente_id;
    public $bairro_id;
    public $usuario_id;
    public $quarteiroes;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['semana_epidemiologica_id', 'agente_id', 'bairro_id', 'usuario_id'], 'required'],
            [['semana_epidemiologica_id', 'agente_id', 'bairro_id', 'usuario_id'], 'integer'],
            [['quarteiroes'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'semana_epidemiologica_id' => 'Semana Epidemiológica',
            'agente_id' => 'Agente',
            'bairro_id' => 'Bairro',
        ];
    }

    public function afterValidate()
    {
        $parent = parent::afterValidate();
        if (!$this->quarteiroes) {
            $this->addError('bairro_id', 'Selecione ao menos um quarteirão');
        }  
        return $parent;
    }

    public function save()
    {
        $quarteiroes = explode(',', $this->quarteiroes);
        foreach ($quarteiroes as $quarteirao) {
            $visita = new SemanaEpidemiologicaVisita;
            $visita->semana_epidemiologica_id = $this->semana_epidemiologica_id;
            $visita->bairro_id = $this->bairro_id;
            $visita->quarteirao_id = $quarteirao;
            $visita->agente_id = $this->agente_id;
            $visita->inserido_por = $this->usuario_id;
            $visita->save();
        }

        return true;
    }
}
