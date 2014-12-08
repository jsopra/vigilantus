<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * ConfiguracaoClienteSearch represents the model behind the search form about ConfiguracaoCliente.
 */
class ConfiguracaoClienteSearch extends SearchModel
{
	public $id;
	public $cliente_id;
	public $valor;

	public function rules()
	{
		return [
			[['id', 'cliente_id'], 'integer'],
			[['valor'], 'safe'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
            'cliente_id' => $this->cliente_id,
        ]);

		$query->andFilterWhere(['like', 'valor', $this->valor]);
        
	}
}
