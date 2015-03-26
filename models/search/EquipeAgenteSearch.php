<?php

namespace app\models\search;

use app\components\SearchModel;

class EquipeAgenteSearch extends SearchModel
{
    public $id;
    public $equipe_id;
    public $codigo;
    public $nome;
    public $ativo;
    public $cliente_id;

    public function rules()
    {
        return [
            [['id', 'equipe_id', 'cliente_id'], 'integer'],
            [['codigo', 'nome', 'ativo'], 'safe'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'equipe_id');
        $this->addCondition($query, 'cliente_id');
        $this->addCondition($query, 'codigo', true);
        $this->addCondition($query, 'nome', true);
        $this->addCondition($query, 'ativo');
    }
}
