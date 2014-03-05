<?php

namespace app\models;

use app\components\ActiveRecord;

/**
 * This is the model class for table "boletim_rg_fechamento".
 *
 * @property integer $id
 * @property integer $boletim_rg_id
 * @property integer $quantidade
 * @property integer $municipio_id
 * @property integer $imovel_tipo_id
 * @property boolean $imovel_lira
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
			[['boletim_rg_id', 'municipio_id', 'imovel_tipo_id'], 'required'],
			[['boletim_rg_id', 'quantidade', 'municipio_id', 'imovel_tipo_id'], 'integer'],
            [['imovel_lira'], 'boolean'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'boletim_rg_id' => 'Boletim RG',
			'quantidade' => 'Quantidade',
            'municipio_id' => 'Município',
            'imovel_tipo_id' => 'Tipo do Imóvel',
            'imovel_lira' => 'Lira?'
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBoletimRg()
	{
		return $this->hasOne(BoletimRg::className(), ['id' => 'boletim_rg_id']);
	}
    
    /**
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
    }
    
    /**
	 * @return \yii\db\ActiveRelation
	 */
	public function getImovelTipo()
	{
		return $this->hasOne(ImovelTipo::className(), ['id' => 'imovel_tipo_id']);
	}
    
    /**
     * Incrementa contage de imóveis em fechamento de boletim
     * 
     * @param BoletimRg $oBoletim
     * @param integer $imovelTipoId
     * @param boolean $lira
     * @return boolean
     */
    public static function incrementaContagemImovel(BoletimRg $oBoletim, $imovelTipoId, $lira) {
        
        $boletimExistente = self::find()
            ->doBoletim($oBoletim->id)
            ->doTipoDeImovel($imovelTipoId)
            ->doTipoLira($lira)
            ->one();
            
        if(!$boletimExistente instanceof self) {
            
            $boletimExistente = new self;
            $boletimExistente->boletim_rg_id = $oBoletim->id;
            $boletimExistente->quantidade = 0;
            $boletimExistente->municipio_id = $oBoletim->municipio_id;
            $boletimExistente->imovel_tipo_id = $imovelTipoId;
            $boletimExistente->imovel_lira = $lira;
            
            if(!$boletimExistente->save())
                return false;
        }
        
        $boletimExistente->quantidade = $boletimExistente->quantidade + 1;
        
        return $boletimExistente->save() ? $boletimExistente : false;
    }
}
