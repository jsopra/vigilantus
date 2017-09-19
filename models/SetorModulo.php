<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "setor_modulos".
 *
 * Estas são as colunas disponíveis na tabela "setor_modulos":
 * @property integer $id
 * @property integer $setor_id
 * @property integer $modulo_id
 *
 * @property Setores $setor
 * @property Modulos $modulo
 */
class SetorModulo extends ActiveRecord 
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'setor_modulos';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['setor_id', 'modulo_id'], 'required'],
			[['setor_id', 'modulo_id'], 'integer']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'setor_id' => 'Setores',
			'modulo_id' => 'Módulo',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCliente()
	{
		return $this->hasOne(Setor::className(), ['id' => 'setor_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getModulo()
	{
		return $this->hasOne(Modulo::className(), ['id' => 'modulo_id']);
	}
}