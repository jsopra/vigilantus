<?php

namespace app\models\search;

use app\components\SearchModel;

class BairroSearch extends SearchModel
{

    public $id;
    public $nome;
    public $municipio_id;
    public $bairro_categoria_id;
    public $cliente_id;

    public function rules()
    {
        return [
            [['id', 'municipio_id', 'bairro_categoria_id', 'cliente_id'], 'integer'],
            [['nome'], 'safe'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'nome', true);
        $this->addCondition($query, 'bairro_categoria_id');
        $this->addCondition($query, 'cliente_id');
        $this->addCondition($query, 'municipio_id');
    }
}
