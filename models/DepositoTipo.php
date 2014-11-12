<?php

namespace app\models;

use app\components\ActiveRecord;
use app\components\ClienteActiveRecord;

/**
 * Este é a classe de modelo da tabela "deposito_tipos".
 *
 * Estas são as colunas disponíveis na tabela "deposito_tipos":
 * @property integer $id
 * @property integer $cliente_id
 * @property integer $deposito_tipo_pai
 * @property string $descricao
 * @property string $sigla
 * @property string $data_cadastro
 * @property string $data_atualizacao
 * @property integer $inserido_por
 * @property integer $atualizado_por
 *
 * @property DepositoTipos $depositoTipoPai
 */
class DepositoTipo extends ClienteActiveRecord 
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'deposito_tipos';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['cliente_id', 'descricao', 'sigla'], 'required'],
			[['cliente_id', 'deposito_tipo_pai', 'inserido_por', 'atualizado_por'], 'integer'],
			[['descricao', 'sigla', 'data_cadastro', 'data_atualizacao'], 'safe'],
            ['cliente_id', 'exist', 'targetClass' => Cliente::className(), 'targetAttribute' => 'id'],
            ['deposito_tipo_pai', 'exist', 'targetClass' => self::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['sigla', 'unique', 'compositeWith' => 'cliente_id'],
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
			'cliente_id' => 'Município Cliente',
			'deposito_tipo_pai' => 'Tipo de Depósito Pai',
			'descricao' => 'Descrição',
			'sigla' => 'Sigla',
            'data_cadastro' => 'Data de Cadastro',
            'data_atualizacao' => 'Data de Atualização',
            'inserido_por' => 'Inserido Por',
            'atualizado_por' => 'Atualizado Por',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getDepositoTipoPai()
	{
		return $this->hasOne(DepositoTipo::className(), ['id' => 'deposito_tipo_pai']);
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
}
