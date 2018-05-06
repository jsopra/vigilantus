<?php

namespace app\models;
use app\components\ClienteActiveRecord;

/**
 * Este é a classe de modelo da tabela "equipe_agentes".
 *
 * Estas são as colunas disponíveis na tabela "equipe_agentes":
 * @property integer $id
 * @property integer $cliente_id
 * @property integer $equipe_id
 * @property integer $usuario_id
 * @property string $codigo
 *
 * @property Clientes $cliente
 * @property Equipes $equipe
 * @property Usuarios $usuario
 */
class EquipeSupervisor extends ClienteActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'equipe_supervisores';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['cliente_id', 'equipe_id', 'usuario_id'], 'required'],
			[['cliente_id', 'equipe_id', 'usuario_id'], 'integer'],
			['codigo', 'safe'],
			[['codigo'], 'string'],
            ['codigo', 'unique', 'compositeWith' => ['cliente_id']],
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
			'equipe_id' => 'Equipe',
			'usuario_id' => 'Usuário',
			'codigo' => 'Código',
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
	public function getEquipe()
	{
		return $this->hasOne(Equipe::className(), ['id' => 'equipe_id']);
	}
	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getUsuario()
	{
		return $this->hasOne(Usuario::className(), ['id' => 'usuario_id']);
	}
}
