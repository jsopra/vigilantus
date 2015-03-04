<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * SocialHashtagSearch represents the model behind the search form about SocialHashtag.
 */
class SocialHashtagSearch extends SearchModel
{
	public $id;
	public $termo;
	public $ativo;
	public $inserido_por;
	public $data_cadastro;
	public $atualizado_por;
	public $data_atualizacao;
	public $cliente_id;

	public function rules()
	{
		return [
			[['id', 'inserido_por', 'atualizado_por', 'cliente_id'], 'integer'],
			[['termo', 'data_cadastro', 'data_atualizacao'], 'safe'],
			[['ativo'], 'boolean'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
            'ativo' => $this->ativo,
            'inserido_por' => $this->inserido_por,
            'data_cadastro' => $this->data_cadastro,
            'atualizado_por' => $this->atualizado_por,
            'data_atualizacao' => $this->data_atualizacao,
            'cliente_id' => $this->cliente_id,
        ]);

		$query->andFilterWhere(['like', 'termo', $this->termo]);
        
	}
}
