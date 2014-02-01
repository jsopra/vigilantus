<?php

namespace app\models;

use app\components\ActiveRecord;

/**
 * This is the model class for table "boletim_rg_fechamento".
 *
 * @property integer $id
 * @property integer $boletim_rg_id
 * @property integer $condicao_imovel_id
 * @property integer $quantidade
 * @property integer $municipio_id
 * @property integer $mes
 * @property integer $ano
 * @property boolean $area_de_foco
 * 
 *
 * @property BoletinsRg $boletimRg
 * @property ImovelCondicoes $condicaoImovel
 */
class BoletimRgFechamento extends ActiveRecord
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
			[['mes', 'ano', 'boletim_rg_id', 'condicao_imovel_id', 'municipio_id'], 'required'],
			[['mes', 'ano', 'boletim_rg_id', 'condicao_imovel_id', 'quantidade', 'municipio_id'], 'integer'],
            [['area_de_foco'], 'boolean'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'boletim_rg_id' => 'Boletim Rg ID',
			'condicao_imovel_id' => 'Condição do Imóvel',
			'quantidade' => 'Quantidade',
            'municipio_id' => 'Município',
            'mes' => 'Mês',
            'ano' => 'Ano',
            'area_de_foco' => 'Área de Foco?'
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
		return $this->hasOne(ImovelCondicao::className(), ['id' => 'condicao_imovel_id']);
	}
    
    /**
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
    }
}
