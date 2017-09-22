<?php

namespace app\models\search;

use app\components\SearchModel;

class SetorModuloSearch extends SearchModel
{
	public $id;
	public $setor_id;
	public $modulo_id;

	public function rules()
	{
		return [
			[['id', 'setor_id', 'modulo_id'], 'integer'],
		];
	}

	public function searchConditions($query)
    {
		$this->addCondition($query, 'id');
		$this->addCondition($query, 'setor_id');
		$this->addCondition($query, 'modulo_id');
	}
}