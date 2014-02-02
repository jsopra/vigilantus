<?php

namespace app\models;

/**
 * This is the model class for table "bairro_ruas".
 *
 * @property integer $id
 * @property integer $municipio_id
 * @property integer $bairro_id
 * @property string $nome
 *
 * @property Municipios $municipio
 * @property Bairros $bairro
 * @property BairroRuaImoveis[] $bairroRuaImoveis
 */
class BairroRua extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'bairro_ruas';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['municipio_id', 'bairro_id', 'nome'], 'required'],
			[['municipio_id', 'bairro_id'], 'integer'],
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
			'bairro_id' => 'Bairro ID',
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
	public function getBairroRuaImoveis()
	{
		return $this->hasMany(BairroRuaImoveis::className(), ['bairro_rua_id' => 'id']);
	}
    
    public static function doBairro($query, $id) {
        $query->andWhere('bairro_id = :id', [':id' => $id]);
    }
    
    public static function daRua($query, $nome) {
        $query->andWhere('nome = :rua', [':rua' => $nome]);
    }
}
