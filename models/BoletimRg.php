<?php

namespace app\models;

/**
 * This is the model class for table "boletins_rg".
 *
 * @property integer $id
 * @property string $data
 * @property integer $folha
 * @property integer $ano
 * @property integer $bairro_id
 * @property integer $quarteirao_numero
 * @property string $seq
 * @property string $data_cadastro
 * @property integer $inserido_por
 *
 * @property BoletimRgImoveis[] $boletimRgImoveis
 * @property BoletimRgFechamento[] $boletimRgFechamentos
 * @property Bairros $bairro
 * @property Usuarios $inseridoPor
 */
class BoletimRg extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'boletins_rg';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['data', 'folha', 'ano', 'bairro_id', 'inserido_por'], 'required'],
			[['data'], 'safe'],
			[['folha', 'ano', 'bairro_id', 'quarteirao_numero', 'inserido_por'], 'integer'],
			[['seq', 'data_cadastro'], 'string']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'data' => 'Data',
			'folha' => 'Folha',
			'ano' => 'Ano',
			'bairro_id' => 'Bairro ID',
			'quarteirao_numero' => 'Quarteirao Numero',
			'seq' => 'Seq',
			'data_cadastro' => 'Data Cadastro',
			'inserido_por' => 'Inserido Por',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBoletimRgImoveis()
	{
		return $this->hasMany(BoletimRgImoveis::className(), ['boletim_rg_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBoletimRgFechamentos()
	{
		return $this->hasMany(BoletimRgFechamento::className(), ['boletim_rg_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBairro()
	{
		return $this->hasOne(Bairros::className(), ['id' => 'bairro_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getInseridoPor()
	{
		return $this->hasOne(Usuarios::className(), ['id' => 'inserido_por']);
	}
}
