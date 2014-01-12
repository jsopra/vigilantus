<?php

namespace app\models\search;

use app\components\SearchModel;

class ImovelCondicaoSearch extends SearchModel
{
    public $id;
    public $municipio_id;
    public $nome;
    public $exibe_nome;
    public $data_cadastro;
    public $data_atualizacao;
    public $inserido_por;
    public $atualizado_por;

    public function rules()
    {
        return [
            [['id', 'municipio_id', 'inserido_por', 'atualizado_por'], 'integer'],
            [['nome', 'data_cadastro', 'data_atualizacao'], 'safe'],
            [['exibe_nome'], 'boolean'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'municipio_id');
        $this->addCondition($query, 'nome', true);
        $this->addCondition($query, 'exibe_nome');
        $this->addCondition($query, 'data_cadastro', true);
        $this->addCondition($query, 'data_atualizacao', true);
        $this->addCondition($query, 'inserido_por');
        $this->addCondition($query, 'atualizado_por');
    }
}
