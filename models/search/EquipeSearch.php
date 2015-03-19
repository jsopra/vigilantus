<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * EquipeSearch represents the model behind the search form about Equipe.
 */
class EquipeSearch extends SearchModel
{
	public $id;
	public $data_criacao;
	public $cliente_id;
	public $nome;

	public function rules()
	{
		return [
			[['id', 'cliente_id'], 'integer'],
			[['data_criacao', 'nome'], 'safe'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
            'data_criacao' => $this->data_criacao,
            'cliente_id' => $this->cliente_id,
        ]);

		$query->andFilterWhere(['like', 'nome', $this->nome]);
        
	}
}
