<?php

namespace app\models\search;

use app\components\SearchModel;

class BairroSearch extends SearchModel
{

    public $id;
    public $municipio_id;
    public $nome;
    public $bairro_categoria_id;

    public function rules()
    {
        return [
            [['id', 'municipio_id', 'bairro_categoria_id'], 'integer'],
            [['nome'], 'safe'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'municipio_id');
        $this->addCondition($query, 'nome', true);
        $this->addCondition($query, 'bairro_categoria_id');
    }
}
