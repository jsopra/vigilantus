<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * DepositoTipoSearch represents the model behind the search form about DepositoTipo.
 */
class DepositoTipoSearch extends SearchModel
{
	public $id;
	public $municipio_id;
	public $deposito_tipo_pai;
	public $descricao;
	public $sigla;

	public function rules()
	{
		return [
			[['id', 'municipio_id', 'deposito_tipo_pai'], 'integer'],
			[['descricao', 'sigla'], 'safe'],
		];
	}

	public function searchConditions($query)
	{
		$this->addCondition($query, 'id');
		$this->addCondition($query, 'municipio_id');
		$this->addCondition($query, 'deposito_tipo_pai');
		$this->addCondition($query, 'descricao', true);
		$this->addCondition($query, 'sigla', true);        
	}
}
