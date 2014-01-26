<?php

namespace app\models;

use app\components\ActiveRecord;

/**
 * This is the model class for table "boletins_rg".
 *
 * @property integer $id
 * @property integer $folha
 * @property integer $ano
 * @property integer $bairro_id
 * @property integer $bairro_quarteirao_id
 * @property string $seq
 * @property string $data_cadastro
 * @property integer $inserido_por
 * @property integer $municipio_id
 * @property integer $categoria_id
 * @property integer $mes
 *
 * @property BoletimRgImoveis[] $boletimRgImoveis
 * @property BoletimRgFechamento[] $boletimRgFechamentos
 * @property Bairros $bairro
 * @property Usuarios $inseridoPor
 */
class BoletimRg extends ActiveRecord
{
    public $categoria_id;
    
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
			[['folha', 'ano', 'mes', 'bairro_id', 'inserido_por', 'municipio_id', 'bairro_quarteirao_id'], 'required'],
            ['bairro_quarteirao_id', 'unique', 'compositeWith' => ['ano', 'mes']],
            ['categoria_id', 'safe'],
            ['folha', 'unique', 'compositeWith' => ['ano']],
            ['mes', 'integer', 'min' => 1, 'max' => 12],
            ['ano', 'integer', 'min' => (date('Y') - 1), 'max' => date('Y')],
			[['folha', 'ano', 'bairro_id', 'quarteirao_id', 'inserido_por', 'municipio_id', 'mes'], 'integer'],
			[['seq', 'data_cadastro'], 'string']
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
			'ano' => 'Ano',
            'mes' => 'Mês',
			'bairro_id' => 'Bairro',
			'bairro_quarteirao_id' => 'Quarteirão',
			'seq' => 'Seq',
			'data_cadastro' => 'Data de Cadastro',
			'inserido_por' => 'Inserido Por',
            'municipio_id' => 'Município',
            'categoria_id' => 'Categoria',
		];
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
		return $this->hasOne(BoletimRgFechamento::className(), ['boletim_rg_id' => 'id']);
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
}
