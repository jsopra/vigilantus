<?php

namespace app\models\report;
use app\models\EquipeAgente;
use app\models\SemanaEpidemiologicaVisita;
use yii\base\Model;


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

    public function getData()
    {
        $visitasAgente = SemanaEpidemiologicaVisita::find()
            ->doAgente($this->agente_id)->all();

        if (count($visitasAgente) == 0) {
            return null;
        }

        return [
            'trabalho_campo' => [
                'imoveis_por_tipo' => [

                ],
                'imoveis' => [

                ],
                'tubitos' => [

                ],
                'pendencias' => [

                ],
                'depositos_inspecionados' => [

                ],
                'depositos_tratamento' => [

                ],
                'adulticida' => [

                ],
                'quarteiroes_trabalhados' => [

                ],
                'quarteiroes_concluidos' => [

                ],
            ],
            'resumo_laboratorio' => [
                'aegypti' => [],
                'albopictus' => [],
                'depositos_com_especimes' => [],
                'imoveis_com_especimes' => [],
                'numero_exemplares' => [],
            ],
        ];
    }
}
