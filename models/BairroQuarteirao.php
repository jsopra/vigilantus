<?php

namespace app\models;

use Yii;
use app\components\ClienteActiveRecord;
use app\models\Query\BairroQuarteiraoQuery as BairroQuarteiraoQuery;

/**
 * This is the model class for table "bairro_quarteiroes".
 *
 * @property integer $id
 * @property integer $municipio_id
 * @property integer $bairro_id
 * @property string $numero_quarteirao
 * @property string $numero_quarteirao_2
 * @property string $data_cadastro
 * @property string $data_atualizacao
 * @property integer $inserido_por
 * @property integer $atualizado_por
 * @property integer $seq;
 * @property string $coordenadas_area
 * @property integer $cliente_id
 * @property string $data_ultimo_foco
 *
 * @property Municipio $municipio
 * @property Cliente $cliente
 * @property Bairros $bairro
 * @property Usuarios $inseridoPor
 * @property Usuarios $atualizadoPor
 */
class BairroQuarteirao extends ClienteActiveRecord
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
			[['municipio_id', 'bairro_id', 'inserido_por', 'atualizado_por', 'seq', 'cliente_id'], 'integer'],
            ['inserido_por', 'required', 'on' => 'insert'],
            ['data_ultimo_foco', 'date'],
            ['atualizado_por', 'required', 'on' => 'update'],
            ['coordenadas', 'required', 'on' => ['insert','update']],
            [['coordenadasJson', 'numero_quarteirao', 'numero_quarteirao_2'], 'string'],
		];
	}

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['updateAttributes'] = ['data_ultimo_foco'];
        return $scenarios;
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
            'cliente_id' => 'Cliente',
            'data_ultimo_foco' => 'Data do Último Foco',
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
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
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
	public function getFocos()
	{
		return $this->hasMany(FocoTransmissor::className(), ['id' => 'bairro_id']);
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

        if($this->coordenadas) {
            return true;
        }

        $this->coordenadas = $this->wktToArray('Polygon', 'coordenadas_area');

        return is_array($this->coordenadas);
    }

    /**
     * Define coordenadas para modelo
     * @return boolean (false em caso de não popular e true em caso de popular)
     */
    public function getAsSRID($srid)
    {

        if($this->coordenadas) {
            return true;
        }

        $this->coordenadas = $this->wktToArray('Polygon', 'coordenadas_area', $srid);

        return is_array($this->coordenadas);
    }

    /**
     * Busca coordenadas de quarteirões do bairro
     * @param array $except
     * @return array
     */
    public static function getCoordenadas(BairroQuarteiraoQuery $quarteiroes)
    {
        $return = [];

        $quarteiroes = $quarteiroes->all();

        foreach($quarteiroes as $quarteirao) {

            $cacheKey = 'quarteirao_coord_' . $quarteirao->id;
            $data = Yii::$app->cache->get($cacheKey);

            if($data !== false) {
                $return[] = $data;
                continue;
            }

            $quarteirao->loadCoordenadas();

            if($quarteirao->coordenadas) {

                $dependency = new \yii\caching\DbDependency;
                $dependency->sql = 'SELECT coalesce(data_atualizacao, data_cadastro) FROM bairro_quarteiroes WHERE id = ' . $quarteirao->id;

                Yii::$app->cache->set($cacheKey, ['numero' => $quarteirao->numero_quarteirao, 'coordenada' => $quarteirao->coordenadas], null, $dependency);

                $return[] = ['numero' => $quarteirao->numero_quarteirao, 'coordenada' => $quarteirao->coordenadas];
            }
        }

        return $return;
    }

    public function getCentro()
    {
        $cacheKey = 'quarteirao_centro_' . $this->id;
        $data = Yii::$app->cache->get($cacheKey);
/*
        if($data !== false) {
            return $data;
        }
*/
        $object = self::find()
            ->select('ST_asText(ST_Centroid(coordenadas_area)) as centro')
            ->where(['id' => $this->id])
            ->one();

        if(!$object instanceof self)
            return false;

        if(strstr($object->centro, 'POINT') === false)
            return false;

        $coordenadas = explode(" ", str_replace(['POINT(', ')'], '', $object->centro));

        if(count($coordenadas) == 0)
            return false;

        $dependency = new \yii\caching\DbDependency;
        $dependency->sql = 'SELECT coalesce(data_atualizacao, data_cadastro) FROM bairro_quarteiroes WHERE id = ' . $this->id;

        Yii::$app->cache->set($cacheKey, $coordenadas, null, $dependency);

        return $coordenadas;
    }

    /**
     * Busca todos ID's de quarteirões em áreas de tramento
     * @uses Caching
     * @param int $clienteId
     * @return array
     */
    public static function getIDsAreaTratamento($clienteId, $especieTransmissor = null, $lira = null)
    {
        $cacheKey = 'quarteiroes_area_tratamento_' . $clienteId;

        if($especieTransmissor !== null) {
            $cacheKey .= '_especie_' . $especieTransmissor;
        }

        if($lira !== null) {
            $cacheKey .= '_lira_' . ($lira === true ? 'true' : 'false');
        }

        $data = Yii::$app->cache->get($cacheKey);

        if($data !== false && !YII_ENV_TEST) {
            return $data;
        }

        $return = [];

        $query = "
            id IN (
                SELECT DISTINCT br.id
                FROM focos_transmissores ft
                JOIN especies_transmissores et ON ft.especie_transmissor_id = et.id
                JOIN bairro_quarteiroes bf on ft.bairro_quarteirao_id = bf.id
                LEFT JOIN imoveis i on ft.imovel_id = i.id
                LEFT JOIN bairro_quarteiroes br	ON ST_DWithin(br.coordenadas_area, ST_Centroid(bf.coordenadas_area), et.qtde_metros_area_foco, true)
                WHERE
                    data_coleta BETWEEN NOW() - INTERVAL '1 DAY' * et.qtde_dias_permanencia_foco AND NOW() AND
                    (quantidade_forma_aquatica > 0 OR quantidade_forma_adulta > 0 OR quantidade_ovos > 0)
                    " . ($especieTransmissor !== null ? ' AND et.id = ' . $especieTransmissor : '') . "
                    " . ($lira ? ($lira === true ? ' AND imovel_lira = TRUE' : ' AND imovel_lira = FALSE') : '') . "
            )
        ";

        $quarteiroes = self::find()->andWhere($query)->all();

        foreach($quarteiroes as $quarteirao) {
            $return[] = $quarteirao->id;
        }

        $dependency = new \yii\caching\DbDependency;
        $dependency->sql = '
            SELECT max(ft.id)
            FROM focos_transmissores ft
            JOIN bairro_quarteiroes bq on ft.bairro_quarteirao_id = bq.id
            WHERE bq.cliente_id = ' . $clienteId;

        Yii::$app->cache->set($cacheKey, $return, null, $dependency);

        return $return;
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
    private function _validateAndLoadPostgisField() {

        if($this->scenario == 'updateAttributes') {
            return true;
        }

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
        foreach (BoletimRg::find()->where('bairro_quarteirao_id = :quarteirao', [':quarteirao' => $this->id])->all() as $registro) {
            $registro->delete();
        }

        foreach (FocoTransmissor::find()->where('bairro_quarteirao_id = :quarteirao', [':quarteirao' => $this->id])->all() as $registro) {
            $registro->delete();
        }

        foreach (Denuncia::find()->where('bairro_quarteirao_id = :quarteirao', [':quarteirao' => $this->id])->all() as $registro) {
            $registro->bairro_quarteirao_id = null;
            $registro->save();
        }
    }
}
