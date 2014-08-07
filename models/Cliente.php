<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "clientes".
 *
 * Estas são as colunas disponíveis na tabela "clientes":
 * @property integer $id
 * @property integer $municipio_id
 * @property string $data_cadastro
 *
 * @property Municipios $municipio
 */
class Cliente extends ActiveRecord 
{
    protected $_validateMunicipio = false;
    
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'clientes';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        return [
			[['municipio_id'], 'required'],
            [['municipio_id'], 'unique'],
            ['municipio_id', 'exist', 'targetClass' => Municipio::className(), 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            [['data_cadastro'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'municipio_id' => 'Município',
			'data_cadastro' => 'Data de Cadastro',
		];
	}
    
    
    /**
     * Busca clientes
     * @param int $id Default is null
     * @return Cliente[] 
     */
    public static function getClientes($id = null) {
        
        $query = self::find();

        if($id)
            $query->andWhere(['"id"' => $id]);
        
        return $query->all();
    }

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getMunicipio()
	{
		return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
	}
    
    /**
     * @return int
     */
    public function getQuantidadeModulos()
    {
        return ClienteModulo::find()->where(['cliente_id' => $this->id])->count();
    }
    
    public function beforeDelete()
    {
        $parent = parent::beforeDelete();

        $this->clearRelationships();

        return $parent;
    }
    
    /**
     * Apaga relações do boletim com imóveis e fechamento de RG
     * @return void
     */
    private function clearRelationships()
    {
        ClienteModulo::deleteAll('cliente_id = :cliente', [':cliente' => $this->id]);
    }
}
