<?php

namespace app\models;
use app\components\ClienteActiveRecord;

/**
 * Este é a classe de modelo da tabela "ocorrencia_historico".
 *
 * Estas são as colunas disponíveis na tabela "ocorrencia_historico":
 * @property integer $id
 * @property integer $cliente_id
 * @property integer $ocorrencia_id
 * @property string $data_hora
 * @property integer $tipo
 * @property string $observacoes
 * @property integer $status_antigo
 * @property integer $status_novo
 * @property integer $usuario_id
 * @property integer $agente_id
 * @property integer $data_associada
 *
 * @property Cliente $cliente
 * @property Ocorrencia $ocorrencia
 */
class OcorrenciaHistorico extends ClienteActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'ocorrencia_historico';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['cliente_id', 'ocorrencia_id', 'tipo'], 'required'],
			[['cliente_id', 'ocorrencia_id', 'tipo', 'status_antigo', 'status_novo', 'usuario_id', 'agente_id'], 'integer'],
			[['data_hora', 'data_associada'], 'safe'],
			[['observacoes'], 'string'],
			['tipo', 'in', 'range' => OcorrenciaHistoricoTipo::getIDs()],
			['status_antigo', 'in', 'range' => OcorrenciaStatus::getIDs()],
			['status_novo', 'in', 'range' => OcorrenciaStatus::getIDs()],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'cliente_id' => 'Município Cliente',
			'ocorrencia_id' => 'Ocorrência',
			'data_hora' => 'Data Hora',
			'tipo' => 'Tipo',
			'observacoes' => 'Observações',
			'status_antigo' => 'Status Antigo',
			'status_novo' => 'Status Novo',
			'usuario_id' => 'Usuário',
			'agente_id' => 'Agente',
			'data_associada' => 'Data Associada',
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
	public function getOcorrencia()
	{
		return $this->hasOne(Ocorrencia::className(), ['id' => 'ocorrencia_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getUsuario()
	{
		return $this->hasOne(Usuario::className(), ['id' => 'usuario_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getAgente()
	{
		return $this->hasOne(EquipeAgente::className(), ['id' => 'agente_id']);
	}
}
