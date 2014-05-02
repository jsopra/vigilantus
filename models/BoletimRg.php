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
			[['folha', 'bairro_id', 'municipio_id', 'bairro_quarteirao_id', 'data'], 'required'],
            [['categoria_id', 'imoveis'], 'safe'],
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
            $this->imoveis[] = [
                'rua' => $imovel->rua_nome ? $imovel->rua_nome : $imovel->imovel->rua->nome,
                'numero' => $imovel->imovel_numero ? $imovel->imovel_numero : $imovel->imovel->numero,
                'seq' => $imovel->imovel_seq ? $imovel->imovel_seq : $imovel->imovel->sequencia,
                'complemento' => $imovel->imovel_complemento ? $imovel->imovel_complemento : $imovel->imovel->complemento,
                'imovel_tipo' => $imovel->imovel_tipo_id,
                'imovel_lira' => $imovel->imovel_lira ? $imovel->imovel_lira : $imovel->imovel->imovel_lira,
            ];
        }

        return;
    }

    /**
     * Insere os imóveis associados com o objeto
     * @return int
     */
    protected function insereImoveis()
    {
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
     * Apaga relações do boletim com imóveis e fechamento de RG
     * @return void
     */
    private function clearRelationships()
    {
        BoletimRgImovel::deleteAll('boletim_rg_id = :boletim', [':boletim' => $this->id]);
        BoletimRgFechamento::deleteAll('boletim_rg_id = :boletim', [':boletim' => $this->id]);
    }
}
