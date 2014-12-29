<?php

namespace app\models;
use app\components\ClienteActiveRecord;

/**
 * This is the model class for table "imoveis".
 *
 * @property integer $id
 * @property integer $municipio_id
 * @property integer $bairro_quarteirao_id
 * @property integer $rua_id
 * @property string $numero
 * @property string $sequencia
 * @property string $complemento
 * @property boolean $imovel_lira
 * @property integer $imovel_tipo_id
 * @property integer $cliente_id
 *
 * @property BoletimRgImoveis[] $boletimRgImovel
 * @property Municipio $municipio
 * @property Cliente $cliente
 * @property BairroRuas $bairroRua
 */
class Imovel extends ClienteActiveRecord
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
			[['municipio_id', 'bairro_quarteirao_id', 'rua_id', 'imovel_tipo_id'], 'required'],
            ['imovel_lira', 'boolean'],
			[['municipio_id', 'bairro_quarteirao_id', 'rua_id', 'imovel_tipo_id', 'cliente_id'], 'integer'],
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
            'bairro_quarteirao_id' => 'Quarteirão',
            'rua_id' => 'Rua',
            'imovel_tipo_id' => 'Tipo do Imóvel',
            'cliente_id' => 'Cliente'
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
		return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCliente()
	{
		return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
	}
    
    /**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBairroQuarteirao()
	{
		return $this->hasOne(BairroQuarteirao::className(), ['id' => 'bairro_quarteirao_id']);
    }
    
    /**
	 * @return \yii\db\ActiveRelation
	 */
	public function getRua()
	{
		return $this->hasOne(Rua::className(), ['id' => 'rua_id']);
    }
    
    /**
	 * @return \yii\db\ActiveRelation
	 */
	public function getImovelTipo()
	{
		return $this->hasOne(ImovelTipo::className(), ['id' => 'imovel_tipo_id']);
	}
}
