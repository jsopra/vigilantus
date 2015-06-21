<?php

namespace app\models;

use Yii;
use app\components\ClienteActiveRecord;

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
 * @property integer $cliente_id
 */
class Bairro extends ClienteActiveRecord
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

    public $centro;

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
            [['municipio_id', 'bairro_categoria_id', 'ultimo_mes_rg', 'ultimo_ano_rg', 'cliente_id'], 'integer'],
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
     * @return \yii\db\ActiveRelation
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
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
     * @return int
     */
    public function getQuantidadeQuarteiroes()
    {
        return BairroQuarteirao::find()->where(['bairro_id' => $this->id])->count();
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
            'cliente_id' => 'Cliente',
        );
    }

    /**
     * Define latitude e longitude para o modelo, caso exista ponto válido cadastrado
     * @return boolean (false em caso de não popular e true em caso de popular)
     */
    public function loadCoordenadas()
    {
        if($this->coordenadas) {
            return true;
        }

        $this->coordenadas = $this->wktToArray('Polygon', 'coordenadas_area');

        return is_array($this->coordenadas);
    }

    public function beforeDelete()
    {
        $parent = parent::beforeDelete();

        $this->_clearRelationships();

        return $parent;
    }

    /**
     * Valida e carrega json de coordenadas em campo postgis
     * @return boolean
     */
    private function _validateAndLoadPostgisField()
    {
        if(!$this->coordenadasJson) {
            $this->addError('coordenadasJson', 'Coordenadas do quarteirão não foram definidas');
            return false;
        }

        $arrayCoordinates = json_decode($this->coordenadasJson);

        $this->coordenadas_area = new \yii\db\Expression($this->arrayToWkt('Polygon', $arrayCoordinates));

        return true;
    }

    /**
     * Apaga relações do boletim com imóveis e fechamento de RG
     * @return void
     */
    private function _clearRelationships()
    {
        foreach (BairroQuarteirao::find()->where('bairro_id = :bairro', [':bairro' => $this->id])->all() as $registro) {
            $registro->delete();
        }

        foreach (Ocorrencia::find()->where('bairro_id = :bairro', [':bairro' => $this->id])->all() as $registro) {
            $registro->bairro_id = null;
            $registro->save();
        }
    }

    public function getCentro()
    {
        $cacheKey = 'bairro_centro_' . $this->id;
        $data = Yii::$app->cache->get($cacheKey);

        if($data !== false) {
            return $data;
        }

        $object = self::find()
            ->select('ST_asText(ST_Centroid(coordenadas_area)) as centro')
            ->where(['id' => $this->id])
            ->one();

        if(!$object instanceof self) {
            return false;
        }

        if(strstr($object->centro, 'POINT') === false) {
            return false;
        }

        $coordenadas = explode(" ", str_replace(['POINT(', ')'], '', $object->centro));
        if(count($coordenadas) == 0) {
            return false;
        }

        Yii::$app->cache->set($cacheKey, $coordenadas, null);

        return $coordenadas;
    }
}
