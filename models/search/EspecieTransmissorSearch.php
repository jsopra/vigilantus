<?php

namespace app\models\search;

use app\components\SearchModel;

class EspecieTransmissorSearch extends SearchModel
{

    public $id;
    public $municipio_id;
    public $nome;

    public function rules()
    {
        return [
            [['id', 'municipio_id'], 'integer'],
            [['nome'], 'safe'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'municipio_id');
        $this->addCondition($query, 'nome', true);
    }
}
