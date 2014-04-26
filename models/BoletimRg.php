<?php

namespace app\models;

use app\components\ActiveRecord;

/**
 * This is the model class for table "boletins_rg".
 *
 * @property integer $id
 * @property integer $folha
 * @property integer $bairro_id
 * @property integer $fee
 * @property string $data_cadastro 
* @property integer $inserido_por
 * @property integer $municipio_id
 * @property integer $categoria_id
 * @property string $data
 *
 * @property BoletimRgImovel[] $boletimRgImovel
 * @property BoletimRgFechamento[] $boletimRgFechamentos
 * @property Bairros $bairro
 * @property Usuarios $inseridoPor
 */
class BoletimRg extends ActiveRecord
{
    public $seq;
    public $categoria_id;
    public $imoveis;
    
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'boletins_rg';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['folha', 'bairro_id', 'municipio_id', 'bairro_quarteirao_id', 'imoveis', 'data'], 'required'],
            [['categoria_id'], 'safe'],
            ['folha', 'unique', 'compositeWith' => ['data', 'municipio_id']],
            ['data', 'date'],
			[['folha', 'bairro_id', 'bairro_quarteirao_id', 'inserido_por', 'municipio_id', 'seq'], 'integer'],
			[['data_cadastro'], 'string'],
	];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'folha' => 'Folha nº',
			'bairro_id' => 'Bairro',
			'bairro_quarteirao_id' => 'Quarteirão',
			'seq' => 'Seq',
			'data_cadastro' => 'Data de Cadastro',
			'inserido_por' => 'Inserido Por',
            'municipio_id' => 'Município',
            'categoria_id' => 'Categoria',
            'data' => 'Data da Coleta',
		];
	}
    
    public function save($runValidation = true, $attributes = null) {

        $transaction = $this->getDb()->beginTransaction();
        try {
            
            $result = parent::save($runValidation, $attributes);
            
            if ($result) {
                
                if(!$this->isNewRecord)
                    $this->_clearRelationships();
                
                $imoveisSalvos = 0;
                $imoveis = $this->imoveis;
                foreach($imoveis as $imovelPreenchido) {
                    $rua = Rua::find()->daRua($imovelPreenchido['rua'])->one();
                    if(!$rua instanceof Rua) {
                        $rua = new Rua;
                        $rua->municipio_id = $this->municipio_id;
                        $rua->nome = $imovelPreenchido['rua'];
                        
                        if(!$rua->save())
                            continue;
                    }
                    
                    $imovel = Imovel::find()
                        ->doQuarteirao($this->bairro_quarteirao_id)
                        ->doNumero($imovelPreenchido['numero'])
                        ->daSeq($imovelPreenchido['seq'])
                        ->doComplemento($imovelPreenchido['complemento'])
                        ->one();
                
                    if(!$imovel instanceof Imovel) {
                        $imovel = new Imovel;
                        $imovel->municipio_id = $this->municipio_id;
                        $imovel->bairro_quarteirao_id = $this->bairro_quarteirao_id;
                        $imovel->imovel_tipo_id = isset($imovelPreenchido['imovel_tipo']) ? $imovelPreenchido['imovel_tipo'] : null;
                        $imovel->rua_id = $rua->id;
                        $imovel->numero = isset($imovelPreenchido['numero']) ? $imovelPreenchido['numero'] : null;
                        $imovel->sequencia = isset($imovelPreenchido['seq']) ? $imovelPreenchido['seq'] : null;
                        $imovel->complemento = isset($imovelPreenchido['complemento']) ? $imovelPreenchido['complemento'] : null;
                        $imovel->imovel_lira = isset($imovelPreenchido['imovel_lira']) && $imovelPreenchido['imovel_lira'] == '1';
                        
                        if(!$imovel->save())
                            continue;
                    }
                    else {
                        
                        $rgIsLira = isset($imovelPreenchido['imovel_lira']) && $imovelPreenchido['imovel_lira'] == '1';
                        $modelIsLira = $imovel->imovel_lira;
                        if($rgIsLira != $modelIsLira) {
                            
                            $imovel->imovel_lira = $rgIsLira;
                            if(!$imovel->save())
                                continue;
                        }
                    }
                    
                    $boletimImovel = new BoletimRgImovel;
                    $boletimImovel->municipio_id = $this->municipio_id;
                    $boletimImovel->imovel_tipo_id = isset($imovelPreenchido['imovel_tipo']) ? $imovelPreenchido['imovel_tipo'] : null;
                    $boletimImovel->boletim_rg_id = $this->id;
                    $boletimImovel->rua_id = $rua->id;
                    $boletimImovel->rua_nome = $imovelPreenchido['rua'];
                    $boletimImovel->imovel_numero = isset($imovelPreenchido['numero']) ? $imovelPreenchido['numero'] : null;
                    $boletimImovel->imovel_seq = isset($imovelPreenchido['seq']) ? $imovelPreenchido['seq'] : null;
                    $boletimImovel->imovel_complemento = isset($imovelPreenchido['complemento']) ? $imovelPreenchido['complemento'] : null;
                    $boletimImovel->imovel_lira = isset($imovelPreenchido['imovel_lira']) && $imovelPreenchido['imovel_lira'] == '1';
                    $boletimImovel->imovel_id = $imovel->id;

                    if ($boletimImovel->save())
                        $imoveisSalvos++;
                }
                
                if ($imoveisSalvos == 0) {
                    $transaction->rollback();
                    $this->addError('imoveis', 'Nenhum imóvel salvo');
                    return false;
                }
                
                $transaction->commit();
            } 
            else {
                $transaction->rollback();
            }
        } 
        catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
        
        return $result;
    }
    
	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBoletimImoveis()
	{
		return $this->hasMany(BoletimRgImovel::className(), ['boletim_rg_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBoletimFechamento()
	{
		return $this->hasMany(BoletimRgFechamento::className(), ['boletim_rg_id' => 'id']);
	}

    /**
     * @return \yii\db\ActiveRelation
     */
    public function getBoletinsFechamento()
    {
        return $this->hasMany(BoletimRgFechamento::className(), ['boletim_rg_id' => 'id']);
    }

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBairro()
	{
		return $this->hasOne(Bairro::className(), ['id' => 'bairro_id']);
	}
    
    /**
	 * @return \yii\db\ActiveRelation
	 */
	public function getQuarteirao()
	{
		return $this->hasOne(BairroQuarteirao::className(), ['id' => 'bairro_quarteirao_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getInseridoPor()
	{
		return $this->hasOne(Usuarios::className(), ['id' => 'inserido_por']);
	}
        
    /**
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
    }
    
    /**
     * @return int
     */
    public function getQuantidadeImoveis() 
    {
        return BoletimRgImovel::find()->where(['boletim_rg_id' => $this->id])->count();
    }

    public function beforeDelete() {
        
        $parent = parent::beforeDelete();
        
        $this->_clearRelationships();
        
        return $parent;
    }
    
    /**
     * Popula imóveis cadastrados em $this->imoveis
     * @return void
     */
    public function populaImoveis() {
        
        $imoveis = $this->boletimImoveis;
        
        foreach($imoveis as $imovel)
            $this->imoveis[] = [
                'rua' => $imovel->rua_nome ? $imovel->rua_nome : $imovel->imovel->rua->nome,
                'numero' => $imovel->imovel_numero ? $imovel->imovel_numero : $imovel->imovel->numero,
                'seq' => $imovel->imovel_seq ? $imovel->imovel_seq : $imovel->imovel->sequencia,
                'complemento' => $imovel->imovel_complemento ? $imovel->imovel_complemento : $imovel->imovel->complemento,
                'imovel_tipo' => $imovel->imovel_tipo_id,
                'imovel_lira' => $imovel->imovel_lira ? $imovel->imovel_lira : $imovel->imovel->imovel_lira,
            ];
        
        return;
    }
    
    /**
     * Apaga relações do boletim com imóveis e fechamento de RG 
     * @return void
     */
    private function _clearRelationships() {
        
        $imoveis = $this->boletimImoveis;
        foreach($imoveis as $imovel)
            $imovel->delete();

        $fechamentos = $this->boletimFechamento;
        foreach($fechamentos as $fechamento)
            $fechamento->delete();
        
        return;
    }
}
