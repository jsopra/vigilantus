<?php

namespace app\models\search;

use app\components\SearchModel;

class BairroQuarteiraoSearch extends SearchModel
{
	public $id;
	public $municipio_id;
	public $bairro_id;
	public $numero_quarteirao;
	public $numero_quarteirao_2;
	public $data_cadastro;
	public $data_atualizacao;
	public $inserido_por;
	public $atualizado_por;

	public function rules()
	{
		return [
			[['id', 'municipio_id', 'bairro_id', 'inserido_por', 'atualizado_por'], 'integer'],
			[['data_cadastro', 'data_atualizacao', 'numero_quarteirao', 'numero_quarteirao_2'], 'safe'],
		];
	}

	public function searchConditions($query)
    {
		$this->addCondition($query, 'id');
		$this->addCondition($query, 'municipio_id');
		$this->addCondition($query, 'bairro_id');
		$this->addCondition($query, 'numero_quarteirao');
		$this->addCondition($query, 'numero_quarteirao_2');
		$this->addCondition($query, 'data_cadastro', true);
		$this->addCondition($query, 'data_atualizacao', true);
		$this->addCondition($query, 'inserido_por');
		$this->addCondition($query, 'atualizado_por');
	}
}
