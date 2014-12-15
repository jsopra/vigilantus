<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * DoencaSearch represents the model behind the search form about Doenca.
 */
class DoencaSearch extends SearchModel
{
	public $id;
	public $data_criacao;
	public $municipio_id;
	public $nome;

	public function rules()
	{
		return [
			[['id', 'municipio_id'], 'integer'],
			[['data_criacao', 'nome'], 'safe'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
            'data_criacao' => $this->data_criacao,
            'municipio_id' => $this->municipio_id,
        ]);

		$query->andFilterWhere(['like', 'nome', $this->nome]);
        
	}
}
