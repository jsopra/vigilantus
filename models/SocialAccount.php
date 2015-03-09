<?php

namespace app\models;
use app\components\ClienteActiveRecord;

/**
 * Este é a classe de modelo da tabela "social_accounts".
 *
 * Estas são as colunas disponíveis na tabela "social_accounts":
 * @property integer $id
 * @property integer $cliente_id
 * @property integer $inserido_por
 * @property string $data_cadastro
 * @property integer $atualizado_por
 * @property string $data_atualizacao
 * @property integer $social
 * @property string $social_id
 * @property string $token
 *
 * @property Clientes $cliente
 * @property Usuarios $inseridoPor
 * @property Usuarios $atualizadoPor
 */
class SocialAccount extends ClienteActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'social_accounts';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['cliente_id', 'inserido_por', 'social', 'social_id', 'token'], 'required'],
			[['cliente_id', 'inserido_por', 'atualizado_por', 'social'], 'integer'],
			[['data_cadastro', 'data_atualizacao'], 'safe'],
			[['social_id', 'token'], 'string']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'cliente_id' => 'Cliente ID',
			'inserido_por' => 'Inserido Por',
			'data_cadastro' => 'Data Cadastro',
			'atualizado_por' => 'Atualizado Por',
			'data_atualizacao' => 'Data Atualizacao',
			'social' => 'Social',
			'social_id' => 'Social ID',
			'token' => 'Token',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCliente()
	{
		return $this->hasOne(Clientes::className(), ['id' => 'cliente_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getInseridoPor()
	{
		return $this->hasOne(Usuarios::className(), ['id' => 'inserido_por']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getAtualizadoPor()
	{
		return $this->hasOne(Usuarios::className(), ['id' => 'atualizado_por']);
	}
}
