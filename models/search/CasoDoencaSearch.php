<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * CasoDoencaSearch represents the model behind the search form about CasoDoenca.
 */
class CasoDoencaSearch extends SearchModel
{
	public $id;
	public $cliente_id;
	public $doenca_id;
	public $inserido_por;
	public $data_cadastro;
	public $atualizado_por;
	public $data_atualizacao;
	public $coordenadas_area;
	public $bairro_quarteirao_id;
	public $nome_paciente;
	public $data_sintomas;

	public function rules()
	{
		return [
			[['id', 'cliente_id', 'doenca_id', 'inserido_por', 'atualizado_por', 'bairro_quarteirao_id'], 'integer'],
			[['data_cadastro', 'data_atualizacao', 'coordenadas_area', 'nome_paciente', 'data_sintomas'], 'safe'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
            'cliente_id' => $this->cliente_id,
            'doenca_id' => $this->doenca_id,
            'inserido_por' => $this->inserido_por,
            'data_cadastro' => $this->data_cadastro,
            'atualizado_por' => $this->atualizado_por,
            'data_atualizacao' => $this->data_atualizacao,
            'bairro_quarteirao_id' => $this->bairro_quarteirao_id,
            'data_sintomas' => $this->data_sintomas,
        ]);

		$query->andFilterWhere(['like', 'coordenadas_area', $this->coordenadas_area])
            ->andFilterWhere(['like', 'nome_paciente', $this->nome_paciente]);
        
	}
}
