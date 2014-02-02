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
 * @property integer $imovel_tipo_id
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
			[['mes', 'ano', 'boletim_rg_id', 'condicao_imovel_id', 'municipio_id', 'imovel_tipo_id'], 'required'],
			[['mes', 'ano', 'boletim_rg_id', 'condicao_imovel_id', 'quantidade', 'municipio_id', 'imovel_tipo_id'], 'integer'],
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
            'mes' => 'Mês',
            'ano' => 'Ano',
			'boletim_rg_id' => 'Boletim RG',
			'condicao_imovel_id' => 'Condição Imóvel',
			'quantidade' => 'Quantidade',
            'municipio_id' => 'Município',
            'imovel_tipo_id' => 'Tipo do Imóvel',
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
    
    /**
	 * @return \yii\db\ActiveRelation
	 */
	public function getImovelTipo()
	{
		return $this->hasOne(ImovelTipo::className(), ['id' => 'imovel_tipo_id']);
	}
    
    public static function doBoletim($query, $id) {
        $query->andWhere('boletim_rg_id = :id', [':id' => $id]);
    }
    
    public static function daCondicaoDeImovel($query, $id) {
        $query->andWhere('condicao_imovel_id = :condicao', [':condicao' => $id]);
    }
    
    public static function doTipoDeImovel($query, $id) {
        $query->andWhere('imovel_tipo_id = :tipo', [':tipo' => $id]);
    }
    
    public static function doTipoDeFoco($query, $foco) {
        $query->andWhere('area_de_foco = :foco', [':foco' => $foco]);
    }
    
    /**
     * Incrementa contage de imóveis em fechamento de boletim
     * 
     * @param BoletimRg $oBoletim
     * @param integer $condicaoImovelId
     * @param integer $imovelTipoId
     * @param boolean $areaDeFoco
     * @return boolean
     */
    public static function incrementaContagemImovel(BoletimRg $oBoletim, $condicaoImovelId, $imovelTipoId, $areaDeFoco) {
        
        $boletimExistente = self::find()
            ->doBoletim($oBoletim->id)
            ->daCondicaoDeImovel($condicaoImovelId)
            ->doTipoDeImovel($imovelTipoId)
            ->doTipoDeFoco($areaDeFoco);
            
        if(!$boletimExistente instanceof self) {
            
            $boletimExistente = new self;
            $boletimExistente->boletim_rg_id = $oBoletim->id;
            $boletimExistente->condicao_imovel_id = $condicaoImovelId;
            $boletimExistente->quantidade = 0;
            $boletimExistente->municipio_id = $oBoletim->municipio_id;
            $boletimExistente->imovel_tipo_id = $imovelTipoId;
            $boletimExistente->mes = $oBoletim->mes;
            $boletimExistente->ano = $oBoletim->ano;
            $boletimExistente->area_de_foco = $areaDeFoco;
            
            if(!$boletimExistente->save())
                return false;
        }
        
        $boletimExistente->quantidade = $boletimExistente->quantidade + 1;
        
        return $boletimExistente->save();
    }
}
