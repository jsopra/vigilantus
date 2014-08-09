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
    public $fechamentos;

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
			[['folha', 'bairro_id', 'municipio_id', 'bairro_quarteirao_id', 'data'], 'required'],
            [['categoria_id', 'imoveis', 'fechamentos'], 'safe'],
            ['folha', 'unique', 'compositeWith' => ['data', 'municipio_id']],
            ['data', 'date'],
			[['folha', 'bairro_id', 'bairro_quarteirao_id', 'inserido_por', 'municipio_id', 'seq'], 'integer'],
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

    public function salvarComImoveis()
    {
        $transaction = $this->getDb()->beginTransaction();

        try {

            if ($result = $this->save()) {

                if (!$this->isNewRecord) {
                    $this->clearRelationships();
                }

                if (($imoveisSalvos = $this->insereImoveis()) == 0) {
                    $transaction->rollback();
                    $this->addError('imoveis', 'Nenhum imóvel salvo');
                    return false;
                }

                $transaction->commit();
            } else {
                $transaction->rollback();
            }
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }

        return $result;
    }
    
    public function salvarComFechamento()
    { 
        $transaction = $this->getDb()->beginTransaction();

        try {

            if ($result = $this->save()) {

                if (!$this->isNewRecord) {
                    $this->clearRelationships();
                }

                if (($fechamentosSalvos = $this->insereFechamentos()) == 0) {
                    $transaction->rollback();
                    $this->addError('fechamentos', 'Nenhum fechamento salvo');
                    return false;
                }

                $transaction->commit();
            } else {
                $transaction->rollback();
            }
        } catch (\Exception $e) {
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
    
    /**
     * @return int
     */
    public function getQuantidadeImoveisFechamento() 
    {
        $qtde = 0;
        
        $queryFechamentos = $this->boletinsFechamento;
        foreach ($queryFechamentos as $fechamento)
            $qtde += $fechamento->quantidade;

        return $qtde;
    }

    public function beforeDelete()
    {
        $parent = parent::beforeDelete();

        $this->clearRelationships();

        return $parent;
    }

    /**
     * Popula imóveis cadastrados em $this->imoveis
     * @return void
     */
    public function popularImoveis()
    {
        $imoveis = $this->boletimImoveis;

        foreach ($imoveis as $imovel) {

            $this->adicionarImovel(
                $imovel->rua_nome ? $imovel->rua_nome : $imovel->imovel->rua->nome,
                $imovel->imovel_numero ? $imovel->imovel_numero : $imovel->imovel->numero,
                $imovel->imovel_seq ? $imovel->imovel_seq : $imovel->imovel->sequencia,
                $imovel->imovel_complemento ? $imovel->imovel_complemento : $imovel->imovel->complemento,
                $imovel->imovel_tipo_id,
                $imovel->imovel_lira ? $imovel->imovel_lira : $imovel->imovel->imovel_lira
            );
        }

        return;
    }

    /**
     * @param string $rua
     * @param intgeer $numero
     * @param intgeer $sequencia
     * @param string $complemento
     * @param integer $tipo
     * @param boolean $lira
     */
    public function adicionarImovel($rua, $numero, $sequencia, $complemento, $tipo, $lira)
    {
        if (!is_array($this->imoveis)) {
            $this->imoveis = [];
        }

        $this->imoveis[] = [
            'rua' => $rua,
            'numero' => $numero,
            'seq' => $sequencia,
            'complemento' => $complemento,
            'imovel_tipo' => $tipo,
            'imovel_lira' => $lira,
        ];
    }

    /**
     * Insere os imóveis associados com o objeto
     * @return int
     */
    protected function insereImoveis()
    {
        if (!$this->imoveis) {
            return 0;
        }

        $imoveisSalvos = 0;

        foreach ($this->imoveis as $data) {

            $boletimImovel = new BoletimRgImovel;
            $boletimImovel->municipio_id = $this->municipio_id;
            $boletimImovel->imovel_tipo_id = isset($data['imovel_tipo']) ? $data['imovel_tipo'] : null;
            $boletimImovel->boletim_rg_id = $this->id;
            $boletimImovel->rua_nome = $data['rua'];
            $boletimImovel->imovel_numero = isset($data['numero']) ? $data['numero'] : null;
            $boletimImovel->imovel_seq = isset($data['seq']) ? $data['seq'] : null;
            $boletimImovel->imovel_complemento = isset($data['complemento']) ? $data['complemento'] : null;
            $boletimImovel->imovel_lira = isset($data['imovel_lira']) && $data['imovel_lira'] == '1';

            $boletimImovel->populateRelation('boletimRg', $this);

            $ruaPreparada = $boletimImovel->prepararRua($data['rua']);
            $imovelPreparado = $boletimImovel->prepararImovel();

            if ($ruaPreparada && $imovelPreparado && $boletimImovel->save()) {
                $imoveisSalvos++;
            }
        }

        return $imoveisSalvos;
    }
    
    /**
     * Insere os imóveis associados com o objeto
     * @return int
     */
    protected function insereFechamentos()
    {
        if (!$this->fechamentos)
            return 0;

        $imoveisSalvos = 0;

        foreach ($this->fechamentos as $id => $data) {

            if(isset($data['lira']) && $data['lira'] > 0) {
                
                $boletimFechamento = new BoletimRgFechamento;
                $boletimFechamento->municipio_id = $this->municipio_id;
                $boletimFechamento->boletim_rg_id = $this->id;
                $boletimFechamento->imovel_lira = false;
                $boletimFechamento->imovel_tipo_id = $id;
                $boletimFechamento->quantidade = $data['lira'];

                if ($boletimFechamento->save())
                    $imoveisSalvos++;
            }
            
            if(isset($data['nao_lira']) && $data['nao_lira'] > 0) {
                
                $boletimFechamento = new BoletimRgFechamento;
                $boletimFechamento->municipio_id = $this->municipio_id;
                $boletimFechamento->boletim_rg_id = $this->id;
                $boletimFechamento->imovel_lira = true;
                $boletimFechamento->imovel_tipo_id = $id;
                $boletimFechamento->quantidade = $data['nao_lira'];

                if ($boletimFechamento->save())
                    $imoveisSalvos++;
            }
            
        }

        return $imoveisSalvos;
    }
    
    /**
     * Popula imóveis cadastrados em $this->imoveis
     * @return void
     */
    public function popularFechamento()
    {
        $fechamentos = $this->boletimFechamento;

        foreach ($fechamentos as $fechamento) {

            $this->adicionarFechamento(
                $fechamento->imovel_tipo_id,
                $fechamento->imovel_lira,
                $fechamento->quantidade
            );
        }

        return;
    }
    
    /**
     * @param integer $tipo
     * @param boolean $lira
     * @param integer $quantidade
     */
    public function adicionarFechamento($tipo, $lira, $quantidade)
    {
        if (!is_array($this->fechamentos))
            $this->fechamentos = [];
        
        if (!isset($this->fechamentos[$tipo]))
            $this->fechamentos[$tipo] = [];

        $stringLira = $lira ? 'lira' : 'nao_lira';
        
        if(isset($this->fechamentos[$tipo][$stringLira]))
            continue;

        $this->fechamentos[$tipo][$stringLira] = $quantidade;
    }

    /**
     * Apaga relações do boletim com imóveis e fechamento de RG
     * @return void
     */
    private function clearRelationships()
    {
        BoletimRgImovel::deleteAll('boletim_rg_id = :boletim', [':boletim' => $this->id]);
        BoletimRgFechamento::deleteAll('boletim_rg_id = :boletim', [':boletim' => $this->id]);
    }
}
