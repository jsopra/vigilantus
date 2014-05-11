<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * FocoTransmissorSearch represents the model behind the search form about FocoTransmissor.
 */
class FocoTransmissorSearch extends SearchModel
{
	public $id;
	public $inserido_por;
	public $atualizado_por;
	public $tipo_deposito_id;
	public $especie_transmissor_id;
	public $data_cadastro;
	public $data_atualizacao;
	public $data_entrada;
	public $data_exame;
	public $data_coleta;
	public $quantidade_forma_aquatica;
	public $quantidade_forma_adulta;
	public $quantidade_ovos;
    public $imovel_id;
    public $laboratorio;
    public $tecnico;

	public function rules()
	{
		return [
			[['id', 'inserido_por', 'atualizado_por', 'tipo_deposito_id', 'especie_transmissor_id', 'quantidade_forma_aquatica', 'quantidade_forma_adulta', 'quantidade_ovos', 'imovel_id'], 'integer'],
			[['data_cadastro', 'data_atualizacao', 'data_entrada', 'data_exame', 'data_coleta'], 'date'],
            [['laboratorio', 'tecnico'], 'safe'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
            'inserido_por' => $this->inserido_por,
            'atualizado_por' => $this->atualizado_por,
            'tipo_deposito_id' => $this->tipo_deposito_id,
            'especie_transmissor_id' => $this->especie_transmissor_id,
            'data_entrada' => $this->data_entrada,
            'data_exame' => $this->data_exame,
            'data_coleta' => $this->data_coleta,
            'data_cadastro' => $this->data_cadastro,
            'data_atualizacao' => $this->data_atualizacao,
            'quantidade_forma_aquatica' => $this->quantidade_forma_aquatica,
            'quantidade_forma_adulta' => $this->quantidade_forma_adulta,
            'quantidade_ovos' => $this->quantidade_ovos,
            'imovel_id' => $this->imovel_id,
        ]);

		$query->andFilterWhere(['like', 'laboratorio', $this->laboratorio]);
        $query->andFilterWhere(['like', 'tecnico', $this->tecnico]);
	}
}
