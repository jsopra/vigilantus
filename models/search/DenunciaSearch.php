<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * DenunciaSearch represents the model behind the search form about Denuncia.
 */
class DenunciaSearch extends SearchModel
{
	public $id;
	public $data_criacao;
	public $cliente_id;
	public $nome;
	public $telefone;
	public $bairro_id;
	public $endereco;
	public $imovel_id;
	public $email;
	public $pontos_referencia;
	public $mensagem;
	public $anexo;
	public $tipo_imovel;
	public $localizacao;
	public $status;
	public $nome_original_anexo;
	public $denuncia_tipo_problema_id;
	public $bairro_quarteirao_id;

	public function rules()
	{
		return [
			[['id', 'cliente_id', 'bairro_id', 'imovel_id', 'tipo_imovel', 'localizacao', 'status', 'denuncia_tipo_problema_id', 'bairro_quarteirao_id'], 'integer'],
			[['data_criacao', 'nome', 'telefone', 'endereco', 'email', 'pontos_referencia', 'mensagem', 'anexo', 'nome_original_anexo'], 'safe'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
            'data_criacao' => $this->data_criacao,
            'cliente_id' => $this->cliente_id,
            'bairro_id' => $this->bairro_id,
            'imovel_id' => $this->imovel_id,
            'tipo_imovel' => $this->tipo_imovel,
            'localizacao' => $this->localizacao,
            'status' => $this->status,
            'denuncia_tipo_problema_id' => $this->denuncia_tipo_problema_id,
            'bairro_quarteirao_id' => $this->bairro_quarteirao_id
        ]);

		$query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'telefone', $this->telefone])
            ->andFilterWhere(['like', 'endereco', $this->endereco])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'pontos_referencia', $this->pontos_referencia])
            ->andFilterWhere(['like', 'mensagem', $this->mensagem])
            ->andFilterWhere(['like', 'anexo', $this->anexo])
            ->andFilterWhere(['like', 'nome_original_anexo', $this->nome_original_anexo]);
        
	}
}
