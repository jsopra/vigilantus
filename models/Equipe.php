<?php

namespace app\models;
use app\components\ClienteActiveRecord;

/**
 * Este é a classe de modelo da tabela "equipes".
 *
 * Estas são as colunas disponíveis na tabela "equipes":
 * @property integer $id
 * @property string $data_criacao
 * @property integer $cliente_id
 * @property string $nome
 *
 * @property Clientes $cliente
 */
class Equipe extends ClienteActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'equipes';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['cliente_id','nome'], 'required'],
			[['cliente_id'], 'integer'],
            ['nome', 'unique', 'compositeWith' => ['cliente_id']],
			[['nome'], 'string'],
			[['data_criacao'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'data_criacao' => 'Data Criação',
			'cliente_id' => 'Cliente',
			'nome' => 'Nome',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCliente()
	{
		return $this->hasOne(Clientes::className(), ['id' => 'cliente_id']);
	}



    /**
     * @return int
     */
    public function getQuantidadeAgentes()
    {
        return EquipeAgente::find()->where(['equipe_id' => $this->id])->count();
    }
}
