<?php

namespace app\models;

use app\components\ActiveRecord;

/**
 * This is the model class for table "boletins_rg".
 *
 * @property integer $id
 * @property integer $folha
 * @property integer $bairro_id
 * @property integer $bairro_quarteirao_id
 * @property string $seq
 * @property string $data_cadastro
 * @property integer $inserido_por
 * @property integer $municipio_id
 * @property integer $categoria_id
 * @property string $data
 *
 * @property BoletimRgImoveis[] $boletimRgImoveis
 * @property BoletimRgFechamento[] $boletimRgFechamentos
 * @property Bairros $bairro
 * @property Usuarios $inseridoPor
 */
class BoletimRg extends ActiveRecord
{
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
			[['folha', 'bairro_id', 'bairro_quarteirao_id', 'inserido_por', 'municipio_id'], 'integer'],
			[['seq', 'data_cadastro'], 'string'],
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

    public function afterValidate() {
        
        unset($this->imoveis['exemplo']);
        
        return parent::afterValidate();
    }
    
    public function save($runValidation = true, $attributes = NULL) {

        $transaction = $this->getDb()->beginTransaction();
        try {
            
            $bairroQuarteirao = BairroQuarteirao::find()->doBairro($this->bairro_id)->doNumero($this->bairro_quarteirao_id)->one();
            if(!$bairroQuarteirao instanceof BairroQuarteirao) {

                $bairroQuarteirao = new BairroQuarteirao;
                $bairroQuarteirao->municipio_id = $this->municipio_id;
                $bairroQuarteirao->bairro_id = $this->bairro_id;
                $bairroQuarteirao->numero_quarteirao = $this->bairro_quarteirao_id;
                $bairroQuarteirao->inserido_por = $this->inserido_por;

                if(!$bairroQuarteirao->save()) {
                    $this->addError('bairro_quarteirao', 'Quarteirão não localizado');
                    return false;
                }
            }

            $this->bairro_quarteirao_id = $bairroQuarteirao->id;
            
            $result = parent::save($runValidation, $attributes);
            
            if ($result) {
                
                if(!$this->isNewRecord) {
                    
                    $imoveis = $this->boletimImoveis;
                    foreach($imoveis as $imovel)
                        $imovel->delete();
                    
                    $fechamentos = $this->boletimFechamento;
                    foreach($fechamentos as $fechamento)
                        $fechamento->delete();
                }
                
                $imoveisSalvos = 0;
                $imoveis = $this->imoveis;
                foreach($imoveis as $imovel) {
                 
                    $ruaBairro = BairroRua::find()
                        ->doBairro($this->bairro_id)
                        ->daRua($imovel['rua'])
                        ->one();
                    
                    if(!$ruaBairro instanceof BairroRua) {
                        $ruaBairro = new BairroRua;
                        $ruaBairro->bairro_id = $this->bairro_id;
                        $ruaBairro->municipio_id = $this->municipio_id;
                        $ruaBairro->nome = $imovel['rua'];
                        
                        if(!$ruaBairro->save())
                            continue 2;
                    }
                    
                    $ruaBairroImovel = BairroRuaImovel::find()
                        ->daRua($ruaBairro->id)
                        ->doNumero($imovel['numero'])
                        ->daSeq($imovel['seq'])
                        ->doComplemento($imovel['complemento'])
                        ->one();
                    
                    if(!$ruaBairroImovel instanceof BairroRuaImovel) {
                        $ruaBairroImovel = new BairroRuaImovel;
                        $ruaBairroImovel->municipio_id = $this->municipio_id;
                        $ruaBairroImovel->bairro_rua_id = $ruaBairro->id;
                        $ruaBairroImovel->numero = $imovel['numero'];
                        $ruaBairroImovel->sequencia = $imovel['seq'];
                        $ruaBairroImovel->complemento = $imovel['complemento'];
                        
                        if(!$ruaBairroImovel->save())
                            continue 2;
                    }
                    
                    $boletimImovel = new BoletimRgImoveis;
                    $boletimImovel->municipio_id = $this->municipio_id;
                    $boletimImovel->imovel_tipo_id = $imovel['imovel_tipo'];
                    $boletimImovel->condicao_imovel_id = $imovel['imovel_condicao'];
                    $boletimImovel->boletim_rg_id = $this->id;
                    $boletimImovel->area_de_foco = isset($imovel['existe_foco']) && $imovel['existe_foco'] == '1';
                    $boletimImovel->bairro_rua_imovel_id = $ruaBairroImovel->id;
                    
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
		return $this->hasMany(BoletimRgImoveis::className(), ['boletim_rg_id' => 'id']);
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
        return BoletimRgImoveis::find()->where(['boletim_rg_id' => $this->id])->count();
    }

    public function beforeDelete() {
        
        $parent = parent::beforeDelete();
        
        $boletimImoveis = $this->boletimImoveis;
        foreach($boletimImoveis as $imovel)
            $imovel->delete();
        
        $boletimFechamento = $this->boletimFechamento;
        foreach($boletimFechamento as $fechamento)
            $fechamento->delete();
        
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
                'rua' => $imovel->bairroRuaImovel->bairroRua->nome,
                'numero' => $imovel->bairroRuaImovel->numero,
                'seq' => $imovel->bairroRuaImovel->sequencia,
                'complemento' => $imovel->bairroRuaImovel->complemento,
                'imovel_tipo' => $imovel->imovel_tipo_id,
                'imovel_condicao' => $imovel->condicao_imovel_id,
                'existe_foco' => $imovel->area_de_foco,
            ];
        
        return;
    }
}
