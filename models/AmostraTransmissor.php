<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "amostras_transmissores".
 *
 * Estas são as colunas disponíveis na tabela "amostras_transmissores":
 * @property integer $id
 * @property string $data_criacao
 * @property string $data_atualizacao
 * @property string $data_coleta
 * @property integer $cliente_id
 * @property integer $tipo_deposito_id
 * @property integer $quarteirao_id
 * @property string $endereco
 * @property string $observacoes
 * @property integer $numero_casa
 * @property integer $numero_amostra
 * @property integer $quantidade_larvas
 * @property integer $quantidade_pupas
 */
class AmostraTransmissor extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'amostras_transmissores';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['data_coleta'], 'date', 'time' => true],
			[['cliente_id', 'tipo_deposito_id', 'quarteirao_id', 'observacoes'], 'required'],
			[['cliente_id', 'tipo_deposito_id', 'quarteirao_id', 'numero_casa', 'numero_amostra', 'quantidade_larvas', 'quantidade_pupas'], 'integer'],
			[['endereco', 'observacoes'], 'string']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'data_criacao' => 'Data de Criação',
			'data_atualizacao' => 'Data de Atualização',
			'data_coleta' => 'Data da Coleta',
			'cliente_id' => 'Cliente',
			'tipo_deposito_id' => 'Tipo de Depósito',
			'quarteirao_id' => 'Quarteirão',
			'endereco' => 'Endereço',
			'observacoes' => 'Observações',
			'numero_casa' => 'Número da Casa',
			'numero_amostra' => 'Número da Amostra',
			'quantidade_larvas' => 'Quantidade de Larvas',
			'quantidade_pupas' => 'Quantidade de Pupas',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function extraFields()
	{
		return ['bairro'];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBairroQuarteirao()
	{
		return $this->hasOne(BairroQuarteirao::className(), ['id' => 'quarteirao_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBairro()
	{
		return $this->hasOne(Bairro::className(), ['id' => 'bairro_id'])
            ->via('bairroQuarteirao');
	}

	public function getTipoDeposito()
    {
        return $this->hasOne(DepositoTipo::className(), ['id' => 'tipo_deposito_id']);
    }

}

