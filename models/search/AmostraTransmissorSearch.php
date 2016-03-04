<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * AmostraTransmissorSearch represents the model behind the search form about AmostraTransmissor.
 */
class AmostraTransmissorSearch extends SearchModel
{
	public $id;
	public $data_criacao;
	public $data_atualizacao;
	public $data_coleta;
	public $cliente_id;
	public $tipo_deposito_id;
	public $quarteirao_id;
	public $endereco;
	public $observacoes;
	public $numero_casa;
	public $numero_amostra;
	public $quantidade_larvas;
	public $quantidade_pupas;
    public $foco;

	public function rules()
	{
		return [
			[['id', 'cliente_id', 'tipo_deposito_id', 'quarteirao_id', 'numero_casa', 'numero_amostra', 'quantidade_larvas', 'quantidade_pupas'], 'integer'],
			[['data_criacao', 'data_atualizacao', 'data_coleta', 'endereco', 'observacoes'], 'safe'],
            [['foco'], 'boolean'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
            'data_criacao' => $this->data_criacao,
            'data_atualizacao' => $this->data_atualizacao,
            'data_coleta' => $this->data_coleta,
            'cliente_id' => $this->cliente_id,
            'tipo_deposito_id' => $this->tipo_deposito_id,
            'quarteirao_id' => $this->quarteirao_id,
            'numero_casa' => $this->numero_casa,
            'numero_amostra' => $this->numero_amostra,
            'quantidade_larvas' => $this->quantidade_larvas,
            'quantidade_pupas' => $this->quantidade_pupas,
            'foco' => $this->foco,
        ]);

		$query->andFilterWhere(['like', 'endereco', $this->endereco])
            ->andFilterWhere(['like', 'observacoes', $this->observacoes]);
	}
}
