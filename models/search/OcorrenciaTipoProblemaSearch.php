<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * OcorrenciaTipoProblemaSearch represents the model behind the search form about OcorrenciaTipoProblema.
 */
class OcorrenciaTipoProblemaSearch extends SearchModel
{
	public $id;
	public $cliente_id;
	public $nome;
	public $ativo;
	public $inserido_por;
	public $data_cadastro;
	public $atualizado_por;
	public $data_atualizacao;

	public function rules()
	{
		return [
			[['id', 'cliente_id', 'inserido_por', 'atualizado_por'], 'integer'],
			[['nome', 'data_cadastro', 'data_atualizacao'], 'safe'],
			[['ativo'], 'boolean'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
            'cliente_id' => $this->cliente_id,
            'ativo' => $this->ativo,
            'inserido_por' => $this->inserido_por,
            'data_cadastro' => $this->data_cadastro,
            'atualizado_por' => $this->atualizado_por,
            'data_atualizacao' => $this->data_atualizacao,
        ]);

		$query->andFilterWhere(['like', 'nome', $this->nome]);

	}
}
