<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "denuncia_historico".
 *
 * Estas são as colunas disponíveis na tabela "denuncia_historico":
 * @property integer $id
 * @property integer $municipio_id
 * @property integer $denuncia_id
 * @property string $data_hora
 * @property integer $tipo
 * @property string $observacoes
 * @property integer $status_antigo
 * @property integer $status_novo
 * @property integer $usuario_id
 *
 * @property Municipios $municipio
 * @property Denuncias $denuncia
 */
class DenunciaHistorico extends ActiveRecord 
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'denuncia_historico';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['municipio_id', 'denuncia_id', 'tipo'], 'required'],
			[['municipio_id', 'denuncia_id', 'tipo', 'status_antigo', 'status_novo', 'usuario_id'], 'integer'],
			[['data_hora'], 'safe'],
			[['observacoes'], 'string'],
			['tipo', 'in', 'range' => DenunciaHistoricoTipo::getIDs()],
			['status_antigo', 'in', 'range' => DenunciaStatus::getIDs()],
			['status_novo', 'in', 'range' => DenunciaStatus::getIDs()],
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
			'denuncia_id' => 'Denúncia',
			'data_hora' => 'Data Hora',
			'tipo' => 'Tipo',
			'observacoes' => 'Observações',
			'status_antigo' => 'Status Antigo',
			'status_novo' => 'Status Novo',
			'usuario_id' => 'Usuário'
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
	public function getDenuncia()
	{
		return $this->hasOne(Denuncia::className(), ['id' => 'denuncia_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getUsuario()
	{
		return $this->hasOne(Usuario::className(), ['id' => 'usuario_id']);
	}
}
