<?php

namespace app\models;
use app\components\ClienteActiveRecord;

/**
 * Este é a classe de modelo da tabela "setor_usuarios".
 *
 * Estas são as colunas disponíveis na tabela "setor_usuarios":
 * @property integer $id
 * @property integer $setor_id
 * @property integer $usuario_id
 * @property integer $cliente_id
 */
class SetorUsuario extends ClienteActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'setor_usuarios';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['setor_id', 'usuario_id', 'cliente_id'], 'required'],
			[['setor_id', 'usuario_id', 'cliente_id'], 'integer'],
			['setor_id', 'unique', 'compositeWith' => 'usuario_id'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'setor_id' => 'Setor',
			'usuario_id' => 'Usuario',
			'cliente_id' => 'Cliente',
		];
	}
}
