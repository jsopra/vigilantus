<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * SetorSearch represents the model behind the search form about Setor.
 */
class SetorSearch extends SearchModel
{
	public $id;
	public $nome;
	public $cliente_id;
	public $inserido_por;
	public $data_cadastro;
	public $atualizado_por;
	public $data_atualizacao;

	public function rules()
	{
		return [
			[['id', 'cliente_id', 'inserido_por', 'atualizado_por'], 'integer'],
			[['nome', 'data_cadastro', 'data_atualizacao'], 'safe'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
            'cliente_id' => $this->cliente_id,
            'inserido_por' => $this->inserido_por,
            'data_cadastro' => $this->data_cadastro,
            'atualizado_por' => $this->atualizado_por,
            'data_atualizacao' => $this->data_atualizacao,
        ]);

		$query->andFilterWhere(['like', 'nome', $this->nome]);

	}
}
