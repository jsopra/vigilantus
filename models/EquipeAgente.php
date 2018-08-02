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
 * @property string $nome
 * @property boolean $ativo
 * @property string $codigo
 * @property integer $usuario_id
 *
 * @property Clientes $cliente
 * @property Equipes $equipe
 */
class EquipeAgente extends ClienteActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'equipe_agentes';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['cliente_id', 'equipe_id', 'nome'], 'required'],
			[['cliente_id', 'equipe_id', 'usuario_id'], 'integer'],
			['codigo', 'safe'],
			[['nome', 'codigo'], 'string'],
			[['ativo'], 'boolean'],
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
			'nome' => 'Nome',
			'ativo' => 'Ativo',
			'codigo' => 'Código',
			'usuario_id' => 'Usuário'
		];
	}

	public function getVisitasAgendadas(SemanaEpidemiologica $semana)
	{
		return SemanaEpidemiologicaVisita::find()
			->daSemanaEpidemiologica($semana->id)
			->doAgente($this->id)
			->count();
	}

	public function getVisitasRealizadas(SemanaEpidemiologica $semana)
	{
		return SemanaEpidemiologicaVisita::find()
			->daSemanaEpidemiologica($semana->id)
			->doAgente($this->id)
			->realizada()
			->count();
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
