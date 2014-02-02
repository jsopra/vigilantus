<?php

namespace app\models;

/**
 * This is the model class for table "bairro_rua_imoveis".
 *
 * @property integer $id
 * @property integer $municipio_id
 * @property integer $bairro_rua_id
 * @property string $numero
 * @property string $sequencia
 * @property string $complemento
 *
 * @property BoletimRgImoveis[] $boletimRgImoveis
 * @property Municipios $municipio
 * @property BairroRuas $bairroRua
 */
class BairroRuaImovel extends \yii\db\ActiveRecord
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
			'municipio_id' => 'Municipio ID',
			'bairro_rua_id' => 'Bairro Rua ID',
			'numero' => 'Numero',
			'sequencia' => 'Sequencia',
			'complemento' => 'Complemento',
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

    public static function daRua($query, $id) {
        $query->andWhere('bairro_rua_id = :rua', [':rua' => $id]);
    }
    
    public static function doNumero($query, $numero) {
        $query->andWhere('numero = :numero', [':numero' => $numero]);
    }
    
    public static function daSeq($query, $seq) {
        $query->andWhere('sequencia = :seq', [':seq' => $seq]);
    }
    
    public static function doComplemento($query, $complemento) {
        $query->andWhere('complemento = :compl', [':compl' => $complemento]);
    }
}
