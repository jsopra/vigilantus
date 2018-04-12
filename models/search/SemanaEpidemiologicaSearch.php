<?php

namespace app\models\search;

use app\components\SearchModel;

class SemanaEpidemiologicaSearch extends SearchModel
{

    public $id;
    public $nome;
    public $inicio;
    public $fim;
    public $cliente_id;

    public function rules()
    {
        return [
            [['id', 'cliente_id'], 'integer'],
            [['inicio', 'fim'], 'date'],
            [['nome'], 'safe'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'nome', true);
        $this->addCondition($query, 'cliente_id');
    }
}
