<?php

namespace app\models;
use app\components\ClienteActiveRecord;

/**
 * Este é a classe de modelo da tabela "especie_transmissor_doencas".
 *
 * Estas são as colunas disponíveis na tabela "especie_transmissor_doencas":
 * @property integer $id
 * @property integer $cliente_id
 * @property integer $doenca_id
 * @property integer $especie_transmissor_id
 *
 * @property Cliente $cliente
 * @property Doencas $doenca
 * @property EspeciesTransmissores $especieTransmissor
 */
class EspecieTransmissorDoenca extends ClienteActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'especie_transmissor_doencas';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['cliente_id', 'doenca_id', 'especie_transmissor_id'], 'required'],
			[['cliente_id', 'doenca_id', 'especie_transmissor_id'], 'integer'],
			['doenca_id', 'unique', 'compositeWith' => ['especie_transmissor_id', 'cliente_id']],
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
			'doenca_id' => 'Doença',
			'especie_transmissor_id' => 'Espécie Transmissor',
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
	public function getDoenca()
	{
		return $this->hasOne(Doenca::className(), ['id' => 'doenca_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getEspecieTransmissor()
	{
		return $this->hasOne(EspecieTransmissor::className(), ['id' => 'especie_transmissor_id']);
	}
}
