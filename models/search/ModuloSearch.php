<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * ModuloSearch represents the model behind the search form about Modulo.
 */
class ModuloSearch extends SearchModel
{
	public $id;
	public $nome;
	public $ativo;
	public $data_cadastro;
	public $data_atualizacao;

	public function rules()
	{
		return [
			[['id'], 'integer'],
			[['nome', 'data_cadastro', 'data_atualizacao'], 'safe'],
			[['ativo'], 'boolean'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
            'ativo' => $this->ativo,
            'data_cadastro' => $this->data_cadastro,
            'data_atualizacao' => $this->data_atualizacao,
        ]);

		$query->andFilterWhere(['like', 'nome', $this->nome]);
        
	}
}
