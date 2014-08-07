<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * ClienteSearch represents the model behind the search form about Cliente.
 */
class ClienteSearch extends SearchModel
{
	public $id;
	public $municipio_id;
	public $data_cadastro;

	public function rules()
	{
		return [
			[['id', 'municipio_id'], 'integer'],
			[['data_cadastro'], 'safe'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
            'municipio_id' => $this->municipio_id,
            'data_cadastro' => $this->data_cadastro,
        ]);
        
	}
}
