<?php

namespace app\models;

use app\components\ActiveRecord;

/**
 * This is the model class for table "bairro_quarteiroes".
 *
 * @property integer $id
 * @property integer $municipio_id
 * @property integer $bairro_id
 * @property integer $numero_quarteirao
 * @property integer $numero_quarteirao_2
 * @property string $data_cadastro
 * @property string $data_atualizacao
 * @property integer $inserido_por
 * @property integer $atualizado_por
 *
 * @property Municipios $municipio
 * @property Bairros $bairro
 * @property Usuarios $inseridoPor
 * @property Usuarios $atualizadoPor
 */
class BairroQuarteirao extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'bairro_quarteiroes';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['municipio_id', 'bairro_id', 'numero_quarteirao'], 'required'],
            ['numero_quarteirao', 'unique', 'compositeWith' => ['bairro_id', 'municipio_id']],
            ['numero_quarteirao_2', 'unique', 'compositeWith' => ['bairro_id', 'municipio_id']],
			[['municipio_id', 'bairro_id', 'numero_quarteirao', 'numero_quarteirao_2', 'inserido_por', 'atualizado_por'], 'integer'],
			[['data_cadastro', 'data_atualizacao'], 'string'],
            ['inserido_por', 'required', 'on' => 'insert'],
            ['atualizado_por', 'required', 'on' => 'update'],
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
			'bairro_id' => 'Bairro',
			'numero_quarteirao' => 'Número principal',
			'numero_quarteirao_2' => 'Número alternativo',
			'data_cadastro' => 'Data de Cadastro',
			'data_atualizacao' => 'Data de Atualização',
			'inserido_por' => 'Inserido Por',
			'atualizado_por' => 'Atualizado Por',
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
	public function getBairro()
	{
		return $this->hasOne(Bairro::className(), ['id' => 'bairro_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getInseridoPor()
	{
		return $this->hasOne(Usuario::className(), ['id' => 'inserido_por']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getAtualizadoPor()
	{
		return $this->hasOne(Usuario::className(), ['id' => 'atualizado_por']);
	}
    
    public static function createQuery()
	{
        return parent::createQuery('\app\models\BairroQuarteiraoQuery');
	}
}
