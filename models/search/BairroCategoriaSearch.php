<?php

namespace app\models\search;

use app\components\SearchModel;

class BairroCategoriaSearch extends SearchModel
{
    public $id;
    public $cliente_id;
    public $nome;
    public $data_cadastro;
    public $data_atualizacao;
    public $inserido_por;
    public $atualizado_por;

    public function rules()
    {
        return [
            [['id', 'cliente_id', 'inserido_por', 'atualizado_por'], 'integer'],
            [['nome', 'data_cadastro', 'data_atualizacao'], 'safe'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'cliente_id');
        $this->addCondition($query, 'nome', true);
        $this->addCondition($query, 'data_cadastro', true);
        $this->addCondition($query, 'data_atualizacao', true);
        $this->addCondition($query, 'inserido_por');
        $this->addCondition($query, 'atualizado_por');
    }
}
