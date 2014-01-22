<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * This is the model class for table "boletim_rg_imoveis".
 *
 * @property integer $id
 * @property string $data
 * @property integer $boletim_rg_id
 * @property integer $bairro_rua_imovel_id
 * @property integer $condicao_imovel_id
 * @property integer $municipio_id
 * 
 * @property BoletinsRg $boletimRg
 * @property BairroRuaImoveis $bairroRuaImovel
 * @property ImovelCondicoes $condicaoImovel
 */
class BoletimRgImoveis extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'boletim_rg_imoveis';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['data', 'boletim_rg_id', 'bairro_rua_imovel_id', 'municipio_id', 'condicao_imovel_id'], 'required'],
			[['data'], 'safe'],
			[['boletim_rg_id', 'bairro_rua_imovel_id', 'condicao_imovel_id', 'municipio_id'], 'integer']
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
			'bairro_rua_imovel_id' => 'Bairro Rua Imovel ID',
			'condicao_imovel_id' => 'Condicao Imovel ID',
            'municipio_id' => 'Município',    
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
	public function getBairroRuaImovel()
	{
		return $this->hasOne(BairroRuaImoveis::className(), ['id' => 'bairro_rua_imovel_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCondicaoImovel()
	{
		return $this->hasOne(ImovelCondicoes::className(), ['id' => 'condicao_imovel_id']);
	}
    
    /**
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
    }
}
