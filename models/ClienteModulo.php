<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "cliente_modulos".
 *
 * Estas são as colunas disponíveis na tabela "cliente_modulos":
 * @property integer $id
 * @property integer $cliente_id
 * @property integer $modulo_id
 *
 * @property Clientes $cliente
 * @property Modulos $modulo
 */
class ClienteModulo extends ActiveRecord 
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'cliente_modulos';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['cliente_id', 'modulo_id'], 'required'],
			[['cliente_id', 'modulo_id'], 'integer']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'cliente_id' => 'Cliente',
			'modulo_id' => 'Módulo',
		];
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
	public function getModulo()
	{
		return $this->hasOne(Modulo::className(), ['id' => 'modulo_id']);
	}
}
