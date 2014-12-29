<?php

namespace app\models;

use app\components\ClienteActiveRecord;

/**
 * This is the model class for table "boletim_rg_fechamento".
 *
 * @property integer $id
 * @property integer $boletim_rg_id
 * @property integer $quantidade
 * @property integer $imovel_tipo_id
 * @property boolean $imovel_lira
 * 
 *
 * @property BoletinsRg $boletimRg
 */
class BoletimRgFechamento extends ClienteActiveRecord
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
			[['boletim_rg_id', 'cliente_id', 'imovel_tipo_id'], 'required'],
			[['boletim_rg_id', 'quantidade', 'imovel_tipo_id'], 'integer'],
            ['boletim_rg_id', 'unique', 'compositeWith' => ['imovel_tipo_id', 'imovel_lira', 'cliente_id']],
            [['imovel_lira'], 'boolean'],
            [['boletim_rg_id', 'imovel_tipo_id', 'imovel_lira'], 'validateLira'],
		];
	}

    public function validateLira()
    {
        $fechamentoInverso = self::find()
            ->doBoletim($this->boletim_rg_id)
            ->doTipoDeImovel($this->imovel_tipo_id)
            ->doTipoLira(!$this->imovel_lira)
            ->one();

        if(!$fechamentoInverso) {
            return true;
        }

        if($this->imovel_lira) {
            if($this->quantidade > $fechamentoInverso->quantidade) {
                $this->addError('quantidade', 'Quantidade Lira deve ser menor que total de imóveis do tipo');
                return false;
            }
        }
        else {
            if($this->quantidade < $fechamentoInverso->quantidade) {
                $this->addError('quantidade', 'Quantidade Lira deve ser menor que total de imóveis do tipo');
                return false;
            }
        }
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
     * @return \yii\db\ActiveRelation
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
    }
    
    /**
	 * @return \yii\db\ActiveRelation
	 */
	public function getImovelTipo()
	{
		return $this->hasOne(ImovelTipo::className(), ['id' => 'imovel_tipo_id']);
	}
    
    /**
     * Incrementa contagem de imóveis em fechamento de boletim
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
            $boletimExistente->quantidade = 1;
            $boletimExistente->cliente_id = $oBoletim->cliente_id;
            $boletimExistente->imovel_tipo_id = $imovelTipoId;
            $boletimExistente->imovel_lira = $lira;
            
            if(!$boletimExistente->save()) {
                return false;
            }

            return true;
        }
        
        $boletimExistente->quantidade = $boletimExistente->quantidade + 1;
        
        return $boletimExistente->save() ? $boletimExistente : false;
    }

    /**
     * Decrementa contagem de imóveis em fechamento de boletim
     * 
     * @param BoletimRg $oBoletim
     * @param integer $imovelTipoId
     * @param boolean $lira
     * @return boolean
     */
    public static function decrementaContagemImovel(BoletimRg $oBoletim, $imovelTipoId, $lira) {
        
        $boletimExistente = self::find()
            ->doBoletim($oBoletim->id)
            ->doTipoDeImovel($imovelTipoId)
            ->doTipoLira($lira)
            ->one();

        if(!$boletimExistente || $boletimExistente->quantidade == 0) {      
            return true;
        }
        
        $boletimExistente->quantidade = $boletimExistente->quantidade - 1;
        
        return $boletimExistente->save() ? $boletimExistente : false;
    }
}
