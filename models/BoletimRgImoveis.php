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
 * @property integer $imovel_tipo_id
 * @property boolean $area_de_foco
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
			[['data', 'boletim_rg_id', 'bairro_rua_imovel_id', 'municipio_id', 'condicao_imovel_id', 'imovel_tipo_id'], 'required'],
			[['data', 'area_de_foco'], 'safe'],
            [['area_de_foco'], 'boolean'],
			[['boletim_rg_id', 'bairro_rua_imovel_id', 'condicao_imovel_id', 'municipio_id', 'imovel_tipo_id'], 'integer']
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
			'boletim_rg_id' => 'Boletim RG',
			'bairro_rua_imovel_id' => 'Bairro Rua Imóvel',
			'condicao_imovel_id' => 'Condição do Imóvel',
            'municipio_id' => 'Município',    
            'imovel_tipo_id' => 'Tipo do Imóvel',
            'area_de_foco' => 'Área de foco?',
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
	public function getBairroRuaImovel()
	{
		return $this->hasOne(BairroRuaImovel::className(), ['id' => 'bairro_rua_imovel_id']);
	}
    
    /**
	 * @return \yii\db\ActiveRelation
	 */
	public function getImovelTipo()
	{
		return $this->hasOne(ImovelTipo::className(), ['id' => 'imovel_tipo_id']);
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
    
    
    public function save($runValidation = true, $attributes = NULL) {

        $currentTransaction = $this->getDb()->getTransaction();		
		$newTransaction = $currentTransaction ? null : $this->getDb()->beginTransaction();
        
        try {
            
            $result = parent::save($runValidation, $attributes);
            
            if ($result) {
                
                $boletimFechamento = BoletimRgFechamento::incrementaContagemImovel(
                    $this->boletimRg,
                    $this->condicao_imovel_id,
                    $this->imovel_tipo_id,
                    $this->area_de_foco
                );

                if($boletimFechamento instanceof BoletimRgFechamento) {
                    if($newTransaction)
                        $transaction->commit();
                }
                else {

                    if($newTransaction)
                        $transaction->rollback();
                    
                    $result = false;                    
                }
            } 
            else {
                if($newTransaction)
                    $transaction->rollback();
            }
        } 
        catch (\Exception $e) {
            if($newTransaction)
                $transaction->rollback();
            throw $e;
        }

        return $result;
    }
}
