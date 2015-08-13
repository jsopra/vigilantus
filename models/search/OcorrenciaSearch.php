<?php

namespace app\models\search;

use app\models\Configuracao;
use app\components\SearchModel;

/**
 * OcorrenciaSearch represents the model behind the search form about Ocorrencia.
 */
class OcorrenciaSearch extends SearchModel
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
	public $ocorrencia_tipo_problema_id;
	public $bairro_quarteirao_id;
    public $qtde_dias_aberto;
    public $data_fechamento;

	public function rules()
	{
		return [
			[['id', 'cliente_id', 'bairro_id', 'imovel_id', 'tipo_imovel', 'localizacao', 'status', 'ocorrencia_tipo_problema_id', 'bairro_quarteirao_id', 'qtde_dias_aberto'], 'integer'],
			[['data_criacao', 'nome', 'telefone', 'endereco', 'email', 'pontos_referencia', 'mensagem', 'anexo', 'nome_original_anexo', 'data_fechamento'], 'safe'],
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
            'ocorrencia_tipo_problema_id' => $this->ocorrencia_tipo_problema_id,
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

        $diasVerde = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_OCORRENCIA_VERDE, \Yii::$app->session->get('cliente')->id);
        $diasVermelho = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_OCORRENCIA_VERMELHO, \Yii::$app->session->get('cliente')->id);

        if($this->qtde_dias_aberto == 1) {
            $query->anteriorA($diasVerde);
        } else if($this->qtde_dias_aberto == 2) {
            $query->entre($diasVerde, $diasVermelho);
        } else if($this->qtde_dias_aberto == 3) {
            $query->posteriorA($diasVermelho);
        }

        if($this->data_fechamento == '1') {
            $query->fechada();
        } else if($this->data_fechamento == '0') {
            $query->aberta();
        }
	}
}
