<?php

namespace app\models\search;

use app\components\SearchModel;

class SemanaEpidemiologicaVisitaSearch extends SearchModel
{

    public $id;
    public $semana_epidemiologica_id;
    public $bairro_id;
    public $agente_id;
    public $quarteirao_id;
    public $cliente_id;
    public $visita_status_id;
    public $data_atividade;

    public function rules()
    {
        return [
            [['id', 'semana_epidemiologica_id', 'bairro_id', 'quarteirao_id', 'agente_id', 'visita_status_id', 'cliente_id'], 'integer'],
            [['data_atividade'], 'safe'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');  
        $this->addCondition($query, 'cliente_id');
        $this->addCondition($query, 'bairro_id');
        $this->addCondition($query, 'quarteirao_id');
        $this->addCondition($query, 'visita_status_id');
        $this->addCondition($query, 'agente_id');
    }
}