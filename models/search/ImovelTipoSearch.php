<?php

namespace app\models\search;

use app\components\SearchModel;

class ImovelTipoSearch extends SearchModel
{
    public $id;
    public $municipio_id;
    public $nome;
    public $sigla;
    public $data_cadastro;
    public $data_atualizacao;
    public $inserido_por;
    public $atualizado_por;

    public function rules()
    {
        return [
            [['id', 'municipio_id', 'inserido_por', 'atualizado_por'], 'integer'],
            [['nome', 'sigla', 'data_cadastro', 'data_atualizacao'], 'safe'],
        ];
    }
    
    public function searchScopes($query)
    {
        $query->ativo();
    }

    public function searchConditions($query)
    {
        $query->andWhere('excluido IS FALSE');
        
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'municipio_id');
        $this->addCondition($query, 'nome', true);
        $this->addCondition($query, 'sigla', true);
        $this->addCondition($query, 'data_cadastro', true);
        $this->addCondition($query, 'data_atualizacao', true);
        $this->addCondition($query, 'inserido_por');
        $this->addCondition($query, 'atualizado_por');
    }
}
