<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BairroTipo;

/**
 * BairroTipoSearch represents the model behind the search form about BairroTipo.
 */
class BairroTipoSearch extends Model
{
	public $id;
	public $municipio_id;
	public $nome;
	public $data_cadastro;
	public $data_atualizacao;
	public $inserido_por;
	public $atualizado_por;

	public function rules()
	{
		return [
			[['id', 'municipio_id', 'inserido_por', 'atualizado_por'], 'integer'],
			[['nome', 'data_cadastro', 'data_atualizacao'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'municipio_id' => 'Municipio ID',
			'nome' => 'Nome',
			'data_cadastro' => 'Data Cadastro',
			'data_atualizacao' => 'Data Atualizacao',
			'inserido_por' => 'Inserido Por',
			'atualizado_por' => 'Atualizado Por',
		];
	}

	public function search($params)
	{
		$query = BairroTipo::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'municipio_id');
		$this->addCondition($query, 'nome', true);
		$this->addCondition($query, 'data_cadastro', true);
		$this->addCondition($query, 'data_atualizacao', true);
		$this->addCondition($query, 'inserido_por');
		$this->addCondition($query, 'atualizado_por');
		return $dataProvider;
	}

	protected function addCondition($query, $attribute, $partialMatch = false)
	{
		$value = $this->$attribute;
		if (trim($value) === '') {
			return;
		}
		if ($partialMatch) {
			$query->andWhere(['like', $attribute, $value]);
		} else {
			$query->andWhere([$attribute => $value]);
		}
	}
}
