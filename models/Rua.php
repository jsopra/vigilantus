<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * This is the model class for table "ruas".
 *
 * @property integer $id
 * @property integer $municipio_id
 * @property string $nome
 *
 * @property Municipios $municipio
 * @property Bairros $bairro
 * @property BairroRuaImoveis[] $bairroRuaImoveis
 */
class Rua extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'ruas';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['municipio_id', 'nome'], 'required'],
			[['municipio_id'], 'integer'],
			[['nome'], 'string']
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
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getMunicipio()
	{
		return $this->hasOne(Municipios::className(), ['id' => 'municipio_id']);
	}
}
