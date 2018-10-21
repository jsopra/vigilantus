<?php

namespace app\models\report;
use app\models\EquipeAgente;


class ResumoTrabalhoCampoReport extends Model
{
    public $agente_id;
    public $semana_id;


    public function rules()
    {
        return [
            [['agente_id', 'semana_id'], 'required'],
            ['agente_id', 'exist', 'targetClass' => EquipeAgente::className(), 'targetAttribute' => 'id'],
            ['semana_id', 'exist', 'targetClass' => SemanaEpidemiologica::className(), 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'agente_id' => 'Agente',
            'semana_id' => 'Semana Epidemiológica',
        ];
    }

    public function load()
    {
        return true;
    }
}
