<?php

namespace app\models;

/**
 * This is the model class for table "boletim_rg_fechamento".
 *
 * @property integer $id
 * @property string $data
 * @property integer $boletim_rg_id
 * @property integer $condicao_imovel_id
 * @property integer $quantidade
 *
 * @property BoletinsRg $boletimRg
 * @property ImovelCondicoes $condicaoImovel
 */
class BoletimRgFechamento extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'boletim_rg_fechamento';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['data', 'boletim_rg_id', 'condicao_imovel_id'], 'required'],
			[['data'], 'safe'],
			[['boletim_rg_id', 'condicao_imovel_id', 'quantidade'], 'integer']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'data' => 'Data',
			'boletim_rg_id' => 'Boletim Rg ID',
			'condicao_imovel_id' => 'Condicao Imovel ID',
			'quantidade' => 'Quantidade',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBoletimRg()
	{
		return $this->hasOne(BoletinsRg::className(), ['id' => 'boletim_rg_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCondicaoImovel()
	{
		return $this->hasOne(ImovelCondicoes::className(), ['id' => 'condicao_imovel_id']);
	}
}
