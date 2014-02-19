<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * This is the model class for table "bairro_rua_imoveis".
 *
 * @property integer $id
 * @property integer $municipio_id
 * @property integer $bairro_rua_id
 * @property string $numero
 * @property string $sequencia
 * @property string $complemento
 * @property boolean $imovel_lira
 *
 * @property BoletimRgImoveis[] $boletimRgImoveis
 * @property Municipios $municipio
 * @property BairroRuas $bairroRua
 */
class BairroRuaImovel extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'bairro_rua_imoveis';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['municipio_id', 'bairro_rua_id'], 'required'],
            ['imovel_lira', 'boolean'],
			[['municipio_id', 'bairro_rua_id'], 'integer'],
			[['numero', 'sequencia', 'complemento'], 'string']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'municipio_id' => 'Município',
			'bairro_rua_id' => 'Bairro Rua',
			'numero' => 'Nímero',
			'sequencia' => 'Sequência',
			'complemento' => 'Complemento',
            'imovel_lira' => 'Imóvel Lira',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBoletimRgImoveis()
	{
		return $this->hasMany(BoletimRgImovel::className(), ['bairro_rua_imovel_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getMunicipio()
	{
		return $this->hasOne(Municipios::className(), ['id' => 'municipio_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBairroRua()
	{
		return $this->hasOne(BairroRua::className(), ['id' => 'bairro_rua_id']);
    }
}
