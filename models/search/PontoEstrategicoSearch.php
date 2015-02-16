<?php

namespace app\models\search;

use app\components\SearchModel;

class PontoEstrategicoSearch extends SearchModel
{

    public $id;
    public $descricao;
    public $bairro_quarteirao_id;
    public $cliente_id;

    public function rules()
    {
        return [
            [['id', 'bairro_quarteirao_id', 'cliente_id'], 'integer'],
            [['descricao'], 'safe'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'descricao', true);
        $this->addCondition($query, 'bairro_quarteirao_id');
        $this->addCondition($query, 'cliente_id');
    }
}
