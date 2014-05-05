<?php

namespace app\models;

use app\components\PostgisActiveRecord;

/**
 * Este é a classe de modelo da tabela "bairros".
 *
 * Estas são as colunas disponíveis na tabela 'bairros':
 * @property integer $id
 * @property integer $municipio_id
 * @property string $nome
 * @property integer $bairro_categoria_id
 * @property integer $ultimo_mes_rg
 * @property integer $ultimo_ano_rg
 * @property string $coordenadas_area
 */
class Bairro extends PostgisActiveRecord
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
     * @return string
     */
    public static function tableName()
    {
        return 'bairros';
    }

    /**
     * @return array regras de validação para os atributos do modelo
     */
    public function rules()
    {
        return array(
            [['municipio_id', 'nome'], 'required'],
            [['ultimo_mes_rg', 'ultimo_ano_rg'], 'required', 'on' => 'setAtualizacaoRG'],
            ['nome', 'unique', 'compositeWith' => 'municipio_id'],
            [['municipio_id', 'bairro_categoria_id', 'ultimo_mes_rg', 'ultimo_ano_rg'], 'integer'],
            ['coordenadas', 'required', 'on' => ['insert','update']],
            [['coordenadasJson'], 'string'],
        );
    }
    
    public function beforeValidate() {
        
        $this->_validateAndLoadPostgisField();
        
        return parent::beforeValidate();
    }

    /**
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
    }
    
    /**
     * @return BairroCategoria
     */
    public function getCategoria()
    {
        return $this->hasOne(BairroCategoria::className(), ['id' => 'bairro_categoria_id']);
    }
    
    /**
     * @return BairroCategoria
     */
    public function getQuarteiroes()
    {
        return $this->hasMany(BairroQuarteirao::className(), ['bairro_id' => 'id']);
    }
    
    /**
     * @return BairroCategoria
     */
    public function getRuas()
    {
        return $this->hasMany(BairroRua::className(), ['bairro_id' => 'id']);
    }

    /**
     * @return array descrição dos atributos (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'municipio_id' => 'Município',
            'nome' => 'Nome',
            'bairro_categoria_id' => 'Categoria',
            'ultimo_mes_rg' => 'Último Mês com informações de RG',
            'ultimo_ano_rg' => 'Último Ano com informações de RG',
            'coordenadas_area' => 'Área',
            'coordenadas' => 'Área',
            'coordenadasJson' => 'Área',
        );
    }
    
    /**
     * Define latitude e longitude para o modelo, caso exista ponto válido cadastrado
     * @return boolean (false em caso de não popular e true em caso de popular)
     */
    public function loadCoordenadas() {

        if($this->coordenadas)
            return true;
        
        $this->coordenadas = $this->postgisToArray('Polygon', 'coordenadas_area');        
        
        return is_array($this->coordenadas);
    } 
    
    /**
     * Busca coordenadas de quarteirões do bairro
     * @param array $except
     * @return array 
     */
    public function getCoordenadasQuarteiroes(array $except) {
        
        $return = [];
        
        $quarteiroes = BairroQuarteirao::find()->doBairro($this->id)->comCoordenadas()->all();
        
        foreach($quarteiroes as $quarteirao) {
            
            if(in_array($quarteirao->id,$except)) 
                continue;
            
            $quarteirao->loadCoordenadas();
            $return[] = $quarteirao->coordenadas;
        }
        
        return $return;
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
