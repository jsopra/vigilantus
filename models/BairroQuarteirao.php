<?php

namespace app\models;
use app\components\PostgisActiveRecord;

/**
 * This is the model class for table "bairro_quarteiroes".
 *
 * @property integer $id
 * @property integer $municipio_id
 * @property integer $bairro_id
 * @property integer $numero_quarteirao
 * @property integer $numero_quarteirao_2
 * @property string $data_cadastro
 * @property string $data_atualizacao
 * @property integer $inserido_por
 * @property integer $atualizado_por
 * @property integer $seq;
 * @property string $coordenadas_area
 *
 * @property Municipios $municipio
 * @property Bairros $bairro
 * @property Usuarios $inseridoPor
 * @property Usuarios $atualizadoPor
 */
class BairroQuarteirao extends PostgisActiveRecord
{
    /**
     * Armazena cooordenadas geográficos vindas do mapa ou populadas do banco
     * é um array de arrays, sendo que cada "sub-array" é um array com latitude e longitude
     * ex: [[-1,-2], [2, 3], [5, 5], [4, 6]]
     * @var array
     */
    public $coordenadas;
    
    /**
     * Armazena cooordenadas geográficos vindas do mapa ou populadas do banco
     * @var array
     */
    public $coordenadasJson;
    
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'bairro_quarteiroes';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['municipio_id', 'bairro_id', 'numero_quarteirao'], 'required'],
            ['numero_quarteirao', 'unique', 'compositeWith' => ['bairro_id', 'municipio_id']],
            ['numero_quarteirao_2', 'unique', 'compositeWith' => ['bairro_id', 'municipio_id']],
			[['municipio_id', 'bairro_id', 'numero_quarteirao', 'numero_quarteirao_2', 'inserido_por', 'atualizado_por', 'seq'], 'integer'],
            ['inserido_por', 'required', 'on' => 'insert'],
            ['atualizado_por', 'required', 'on' => 'update'],
            ['coordenadas', 'required', 'on' => ['insert','update']],
            [['coordenadasJson'], 'string'],
		];
	}
    
    public function beforeValidate() {
        
        $this->_validateAndLoadPostgisField();
        
        return parent::beforeValidate();
    }

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'municipio_id' => 'Município',
			'bairro_id' => 'Bairro',
			'numero_quarteirao' => 'Número principal',
			'numero_quarteirao_2' => 'Número alternativo',
			'data_cadastro' => 'Data de Cadastro',
			'data_atualizacao' => 'Data de Atualização',
			'inserido_por' => 'Inserido Por',
			'atualizado_por' => 'Atualizado Por',
            'seq' => 'Sequência',
            'coordenadas_area' => 'Área',
            'coordenadas' => 'Área',
            'coordenadasJson' => 'Área',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getMunicipio()
	{
		return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
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
		return $this->hasOne(Usuario::className(), ['id' => 'inserido_por']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getAtualizadoPor()
	{
		return $this->hasOne(Usuario::className(), ['id' => 'atualizado_por']);
	}
    
    public function getNumero_sequencia()
    {  
        return $this->numero_quarteirao . ($this->seq ? '-' . $this->seq : '');     
    }
    
    /**
     * Define coordenadas para modelo
     * @return boolean (false em caso de não popular e true em caso de popular)
     */
    public function loadCoordenadas() {
        
        if($this->coordenadas)
            return true;
        
        $this->coordenadas = $this->postgisToArray('Polygon', 'coordenadas_area');        
        
        return is_array($this->coordenadas);
    } 
    
    /**
     * Valida e carrega json de coordenadas em campo postgis
     * @return boolean 
     */
    private function _validateAndLoadPostgisField() {
        
        if(!$this->coordenadasJson) {
            $this->addError('coordenadasJson', 'Coordenadas do quarteirão não foram definidas');
            return false;
        }
        
        $this->coordenadas_area = $this->jsonToPostgis('Polygon', $this->coordenadasJson);
        return true;
    }
}
