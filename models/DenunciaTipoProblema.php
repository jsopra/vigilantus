<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "denuncia_tipos_problemas".
 *
 * Estas são as colunas disponíveis na tabela "denuncia_tipos_problemas":
 * @property integer $id
 * @property integer $municipio_id
 * @property string $nome
 * @property boolean $ativo
 * @property integer $inserido_por
 * @property string $data_cadastro
 * @property integer $atualizado_por
 * @property string $data_atualizacao
 *
 * @property Denuncias[] $denuncias
 * @property Municipios $municipio
 * @property Usuarios $inseridoPor
 * @property Usuarios $atualizadoPor
 */
class DenunciaTipoProblema extends ActiveRecord 
{

	public function beforeDelete()
    {
        if ($this->getDenuncias()->count() > 0) {
            throw new \Exception('O tipo tem denúncias vinculadas. Desative-o para não usar mais.');
        }

        return parent::beforeDelete();
    }

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'denuncia_tipos_problemas';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['municipio_id', 'nome', 'inserido_por'], 'required'],
			[['municipio_id', 'inserido_por', 'atualizado_por'], 'integer'],
			[['nome'], 'string'],
			[['ativo'], 'boolean'],
			['nome', 'unique', 'compositeWith' => ['municipio_id']],
			[['data_cadastro', 'data_atualizacao'], 'safe']
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
			'nome' => 'Nome',
			'ativo' => 'Ativo',
			'inserido_por' => 'Inserido Por',
			'data_cadastro' => 'Data Cadastro',
			'atualizado_por' => 'Atualizado Por',
			'data_atualizacao' => 'Data Atualizacao',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getDenuncias()
	{
		return $this->hasMany(Denuncias::className(), ['denuncia_tipo_problema_id' => 'id']);
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
}
