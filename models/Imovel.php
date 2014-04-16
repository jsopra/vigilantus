<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * This is the model class for table "imoveis".
 *
 * @property integer $id
 * @property integer $municipio_id
 * @property integer $bairro_quarteirao_id
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
		return 'imoveis';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['municipio_id', 'bairro_quarteirao_id'], 'required'],
            ['imovel_lira', 'boolean'],
			[['municipio_id', 'bairro_quarteirao_id'], 'integer'],
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
			'numero' => 'Nímero',
			'sequencia' => 'Sequência',
			'complemento' => 'Complemento',
            'imovel_lira' => 'Imóvel Lira',
            'bairro_quarteirao_id' => 'Quarteirão'
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBoletimRgImoveis()
	{
		return $this->hasMany(BoletimRgImovel::className(), ['rua_id' => 'id']);
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
	public function getBairroQuarteirao()
	{
		return $this->hasOne(BairroRua::className(), ['id' => 'bairro_quarteirao_id']);
    }
}
