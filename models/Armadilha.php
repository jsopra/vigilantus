<?php

namespace app\models;
use app\components\ClienteActiveRecord;

/**
 * Este é a classe de modelo da tabela "armadilhas".
 *
 * Estas são as colunas disponíveis na tabela "armadilhas":
 * @property integer $id
 * @property integer $inserido_por
 * @property string $data_cadastro
 * @property integer $atualizado_por
 * @property string $data_atualizacao
 * @property integer $cliente_id
 * @property string $descricao
 * @property string $coordenadas_area
 * @property integer $bairro_quarteirao_id
 *
 * @property Usuarios $inseridoPor
 * @property Usuarios $atualizadoPor
 * @property Clientes $cliente
 * @property BairroQuarteiroes $bairroQuarteirao
 */
class Armadilha extends ClienteActiveRecord
{
	/**
	 * Armazena latitude
	 * @var number
	 */
	public $latitude;

	/**
	 * Armazena longitude
	 * @var number
	 */
    public $longitude;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'armadilhas';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['inserido_por', 'cliente_id', 'descricao'], 'required'],
			[['inserido_por', 'atualizado_por', 'cliente_id', 'bairro_quarteirao_id'], 'integer'],
			[['data_cadastro', 'data_atualizacao'], 'safe'],
			[['descricao'], 'string'],
            [['latitude', 'longitude'], 'required', 'on' => ['insert','update']],
            [['latitude', 'longitude'], 'string'],
		];
	}

    public function beforeValidate() {

        $this->_validateAndLoadPostgisField();

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
    	$this->_setQuarteirao();

    	return parent::beforeSave($insert);
    }

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'inserido_por' => 'Inserido Por',
			'data_cadastro' => 'Data Cadastro',
			'atualizado_por' => 'Atualizado Por',
			'data_atualizacao' => 'Data Atualização',
			'cliente_id' => 'Cliente',
			'descricao' => 'Descrição',
			'coordenadas_area' => 'Coordenadas',
			'bairro_quarteirao_id' => 'Quarteirão',
		];
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
	public function getBairroQuarteirao()
	{
		return $this->hasOne(BairroQuarteirao::className(), ['id' => 'bairro_quarteirao_id']);
	}

	/**
     * Define latitude e longitude para o modelo, caso exista ponto válido cadastrado
     * @return boolean (false em caso de não popular e true em caso de popular)
     */
    public function loadCoordenadas()
    {
        if(!$this->coordenadas_area) {
            return false;
        }

        if($this->longitude && $this->latitude) {
            return true;
        }

        list($this->longitude, $this->latitude) = $this->wktToArray('Point', 'coordenadas_area');

        return true;
    }

    /**
     * Seta o quarteirão para dadas coordenadas
     * @return void
     */
    private function _setQuarteirao()
    {
    	$coordenadas = $this->arrayToWkt('Point', [$this->longitude, $this->latitude]);

    	$bairroQuarteirao = BairroQuarteirao::find()->pontoNaArea($coordenadas)->one();
    	if($bairroQuarteirao) {
    		$this->bairro_quarteirao_id = $bairroQuarteirao->id;
    	}
    	else {
    		$this->bairro_quarteirao_id = null;
    	}

    	return;
    }

    /**
     * Valida e carrega json de coordenadas em campo postgis
     * @return boolean
     */
    private function _validateAndLoadPostgisField()
    {
        if(!$this->latitude) {
            $this->addError('latitude', 'Coordenadas não foram definidas');
            return false;
        }

        if(!$this->longitude) {
            $this->addError('longitude', 'Coordenadas não foram definidas');
            return false;
        }

        $this->coordenadas_area = new \yii\db\Expression($this->arrayToWkt('Point', [$this->longitude, $this->latitude]));

        return true;
    }
}
