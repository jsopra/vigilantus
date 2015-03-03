<?php

namespace app\models;
use Yii;
use app\components\ClienteActiveRecord;

/**
 * Este é a classe de modelo da tabela "social_hashtags".
 *
 * Estas são as colunas disponíveis na tabela "social_hashtags":
 * @property integer $id
 * @property string $termo
 * @property boolean $ativo
 * @property integer $inserido_por
 * @property string $data_cadastro
 * @property integer $atualizado_por
 * @property string $data_atualizacao
 * @property integer $cliente_id
 *
 * @property Usuarios $inseridoPor
 * @property Usuarios $atualizadoPor
 * @property Clientes $cliente
 */
class SocialHashtag extends ClienteActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'social_hashtags';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			['termo', 'required'],
			['termo', 'unique', 'compositeWith' => ['cliente_id']],
			[['termo'], 'string'],
			[['ativo'], 'boolean'],
			[['inserido_por', 'cliente_id'], 'required'],
			[['inserido_por', 'atualizado_por', 'cliente_id'], 'integer'],
			[['data_cadastro', 'data_atualizacao'], 'safe'],
			['termo', 'validaQuantidadeRegistrosCliente'],
		];
	}

	public function validaQuantidadeRegistrosCliente($attribute, $params)
    {
        if (Yii::$app->params['maximoTermosMonitoramentoRedesSociaisPorCliente'] <= self::find()->count()) {
            $this->addError('termo', 'Seu minicípio só pode cadastrar ' . Yii::$app->params['maximoTermosMonitoramentoRedesSociaisPorCliente'] . ' termos');
        }
    }

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'termo' => 'Termo',
			'ativo' => 'Ativo',
			'inserido_por' => 'Inserido Por',
			'data_cadastro' => 'Data Cadastro',
			'atualizado_por' => 'Atualizado Por',
			'data_atualizacao' => 'Data Atualização',
			'cliente_id' => 'Cliente',
		];
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

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCliente()
	{
		return $this->hasOne(Clientes::className(), ['id' => 'cliente_id']);
	}
}
