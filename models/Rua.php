<?php

namespace app\models;
use app\components\ClienteActiveRecord;

/**
 * This is the model class for table "ruas".
 *
 * @property integer $id
 * @property integer $municipio_id
 * @property string $nome
 * @property integer $cliente_id
 *
 * @property Municipio $municipio
 * @property Cliente $cliente
 * @property Bairros $bairro
 * @property BairroRuaImoveis[] $bairroRuaImoveis
 */
class Rua extends ClienteActiveRecord
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
			[['municipio_id', 'cliente_id'], 'integer'],
            ['nome', 'unique', 'compositeWith' => 'municipio_id'],
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
			'municipio_id' => 'Municipio',
			'nome' => 'Nome',
			'cliente_id' => 'Cliente'
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getMunicipio()
	{
		return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCliente()
	{
		return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
	}
}
