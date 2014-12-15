<?php

namespace app\models;
use app\components\ClienteActiveRecord;

/**
 * Este é a classe de modelo da tabela "doencas".
 *
 * Estas são as colunas disponíveis na tabela "doencas":
 * @property integer $id
 * @property string $data_criacao
 * @property integer $cliente_id
 * @property string $nome
 *
 * @property Cliente $cliente
 */
class Doenca extends ClienteActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'doencas';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['data_criacao'], 'safe'],
			[['cliente_id'], 'required'],
			[['cliente_id'], 'integer'],
			['nome', 'unique', 'compositeWith' => ['cliente_id']],
			[['nome'], 'string']
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
		return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
	}

	public function beforeDelete()
    {
        $parent = parent::beforeDelete();

        $this->clearRelationships();

        return $parent;
    }

    /**
     * Apaga relações do boletim com imóveis e fechamento de RG
     * @return void
     */
    private function clearRelationships()
    {
        EspecieTransmissorDoenca::deleteAll('doenca_id = :doenca', [':doenca' => $this->id]);
    }
}
