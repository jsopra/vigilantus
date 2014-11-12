<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * ClienteSearch represents the model behind the search form about Cliente.
 */
class ClienteSearch extends SearchModel
{
	public $id;
	public $municipio_id;
	public $data_cadastro;
	public $nome_contato;
	public $email_contato;
	public $telefone_contato;
	public $departamento;
	public $cargo;

	public function rules()
	{
		return [
			[['id', 'municipio_id'], 'integer'],
			[['data_cadastro', 'nome_contato', 'email_contato', 'telefone_contato', 'departamento', 'cargo'], 'safe'],
		];
	}

	public function searchConditions($query)
	{
		$query->andFilterWhere([
            'id' => $this->id,
            'municipio_id' => $this->municipio_id,
            'data_cadastro' => $this->data_cadastro,
            'nome_contato' => $this->data_cadastro,
            'email_contato' => $this->email_contato,
            'telefone_contato' => $this->telefone_contato,
            'departamento' => $this->departamento,
            'cargo' => $this->cargo
        ]);
        
	}
}
