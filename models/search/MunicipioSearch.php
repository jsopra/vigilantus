<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * MunicipioSearch represents the model behind the search form about Municipio.
 */
class MunicipioSearch extends SearchModel
{
	public $id;
	public $nome;
	public $sigla_estado;
	public $coordenadas_area;

	public function rules()
	{
		return [
			[['id'], 'integer'],
			[['nome', 'sigla_estado', 'coordenadas_area'], 'safe'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
        ]);

		$query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'sigla_estado', $this->sigla_estado])
            ->andFilterWhere(['like', 'coordenadas_area', $this->coordenadas_area]);
        
	}
}
