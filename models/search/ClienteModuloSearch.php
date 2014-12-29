<?php

namespace app\models\search;

use app\components\SearchModel;

class ClienteModuloSearch extends SearchModel
{
	public $id;
	public $cliente_id;
	public $modulo_id;

	public function rules()
	{
		return [
			[['id', 'cliente_id', 'modulo_id'], 'integer'],
		];
	}

	public function searchConditions($query)
    {
		$this->addCondition($query, 'id');
		$this->addCondition($query, 'cliente_id');
		$this->addCondition($query, 'modulo_id');
	}
}
