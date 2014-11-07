<?php

namespace app\models;

use app\components\ActiveRecord;
/**
 * This is the model class for table "boletim_rg_imoveis".
 *
 * @property integer $id
 * @property integer $boletim_rg_id
 * @property integer $imovel_id
 * @property integer $municipio_id
 * @property integer $imovel_tipo_id
 * @property string $rua_nome
 * @property integer $rua_id
 * @property integer $imovel_numero
 * @property integer $imovel_seq
 * @property integer $imovel_complemento
 * @property boolean $imovel_lira
 * 
 * @property BoletinsRg $boletimRg
 * @property BairroRuaImoveis $bairroRuaImovel
 */
class BoletimRgImovel extends ActiveRecord
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
			[['boletim_rg_id', 'imovel_id', 'municipio_id', 'imovel_tipo_id', 'rua_nome', 'rua_id'], 'required'],
            ['imovel_lira', 'boolean'],
			[['boletim_rg_id', 'imovel_id', 'municipio_id', 'imovel_tipo_id', 'rua_id'], 'integer'],
            [['imovel_numero', 'imovel_seq', 'imovel_complemento'], 'string']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'boletim_rg_id' => 'Boletim RG',
			'imovel_id' => 'Bairro Rua Imóvel',
            'municipio_id' => 'Município',    
            'imovel_tipo_id' => 'Tipo do Imóvel',
            'imovel_numero' => 'Nímero',
			'imovel_seq' => 'Sequência',
			'imovel_complemento' => 'Complemento',
            'imovel_lira' => 'Imóvel Lira',
            'rua_id' => 'Rua',		
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
	public function getImovel()
	{
		return $this->hasOne(Imovel::className(), ['id' => 'imovel_id']);
	}
    
    /**
	 * @return \yii\db\ActiveRelation
	 */
	public function getImovelTipo()
	{
		return $this->hasOne(ImovelTipo::className(), ['id' => 'imovel_tipo_id']);
	}
    
    /**
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
    }
    
    /**
     * @return Municipio
     */
    public function getRua()
    {
        return $this->hasOne(Rua::className(), ['id' => 'rua_id']);
    }

    /**
     * Busca ou cria um objeto Rua, e seta o $this->rua_id
     * @param string $nomeRua
     * @return boolean
     */
    public function prepararRua($nomeRua)
    {
        $rua = Rua::find()->daRua($nomeRua)->one();

        if (!$rua) {

            $rua = new Rua;
            $rua->municipio_id = $this->municipio_id;
            $rua->nome = $nomeRua;

            if (!$rua->save()) {
                return false;
            }
        }

        $this->rua_id = $rua->id;

        return true;
    }

    /**
     * Busca ou cria um objeto Imovel, e seta o $this->imovel_id
     * @return boolean
     */
    public function prepararImovel()
    {
        $imovel = Imovel::find()
            ->daRua($this->rua_id)
            ->doQuarteirao($this->boletimRg->bairro_quarteirao_id)
            ->doNumero($this->imovel_numero)
            ->daSeq($this->imovel_seq)
            ->doComplemento($this->imovel_complemento)
            ->one()
        ;

        if ($imovel) {

            if ($this->imovel_lira != $imovel->imovel_lira) {

                $imovel->imovel_lira = $this->imovel_lira;

                if (!$imovel->save()) {
                    return false;
                }
            }
        } else {

            $imovel = new Imovel;
            $imovel->municipio_id = $this->municipio_id;
            $imovel->bairro_quarteirao_id = $this->boletimRg->bairro_quarteirao_id;
            $imovel->imovel_tipo_id = $this->imovel_tipo_id;
            $imovel->rua_id = $this->rua_id;
            $imovel->numero = $this->imovel_numero;
            $imovel->sequencia = $this->imovel_seq;
            $imovel->complemento = $this->imovel_complemento;
            $imovel->imovel_lira = $this->imovel_lira;

            if (!$imovel->save()) {
                return false;
            }
        }

        $this->imovel_id = $imovel->id;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function save($runValidation = true, $attributes = NULL) {

        $currentTransaction = $this->getDb()->getTransaction();		
		$newTransaction = $currentTransaction ? null : $this->getDb()->beginTransaction();
        
        try {
            
            $result = parent::save($runValidation, $attributes);
            
            if ($result) {

                $boletimFechamentoInverso = true;
                if($this->imovel->imovel_lira) {
                    $boletimFechamentoInverso = BoletimRgFechamento::incrementaContagemImovel(
                        $this->boletimRg,
                        $this->imovel_tipo_id,
                        false
                    );
                }
                
                $boletimFechamento = BoletimRgFechamento::incrementaContagemImovel(
                    $this->boletimRg,
                    $this->imovel_tipo_id,
                    $this->imovel->imovel_lira
                );

                if($boletimFechamento && $boletimFechamentoInverso) {

                    if($newTransaction) {
                        $newTransaction->commit();
                    }
                }
                else {
                    if($newTransaction) {
                        $newTransaction->rollback();
                    }
                    
                    $result = false;                    
                }
            } 
            else {
                if($newTransaction) {
                    $newTransaction->rollback();
                }
            }
        } 
        catch (\Exception $e) {
            if($newTransaction) {
                $newTransaction->rollback();
            }
            throw $e;
        }

        return $result;
    }

    public function beforeDelete()
    {
        $parent = parent::beforeDelete();

        if($this->imovel->imovel_lira == true) {

            BoletimRgFechamento::decrementaContagemImovel(
                $this->boletimRg,
                $this->imovel_tipo_id,
                false
            );
        }

        BoletimRgFechamento::decrementaContagemImovel(
            $this->boletimRg,
            $this->imovel_tipo_id,
            $this->imovel->imovel_lira
        );

        return $parent;
    }
}
