<?php

namespace app\models;

use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "deposito_tipos".
 *
 * Estas são as colunas disponíveis na tabela "deposito_tipos":
 * @property integer $id
 * @property integer $municipio_id
 * @property integer $deposito_tipo_pai
 * @property string $descricao
 * @property string $sigla
 * @property string $data_cadastro
 * @property string $data_atualizacao
 * @property integer $inserido_por
 * @property integer $atualizado_por
 *
 * @property Municipios $municipio
 * @property DepositoTipos $depositoTipoPai
 */
class DepositoTipo extends ActiveRecord 
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
			[['municipio_id', 'descricao', 'sigla'], 'required'],
			[['municipio_id', 'deposito_tipo_pai', 'inserido_por', 'atualizado_por'], 'integer'],
			[['descricao', 'sigla', 'data_cadastro', 'data_atualizacao'], 'safe'],
            ['municipio_id', 'exist', 'targetClass' => Municipio::className(), 'targetAttribute' => 'id'],
            ['deposito_tipo_pai', 'exist', 'targetClass' => self::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            ['sigla', 'unique', 'compositeWith' => 'municipio_id'],
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
			'municipio_id' => 'Município',
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
	public function getMunicipio()
	{
		return $this->hasOne(Municipios::className(), ['id' => 'municipio_id']);
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
