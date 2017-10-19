<?php

namespace app\models;

use Yii;
use app\components\ClienteActiveRecord;

/**
 * This is the model class for table "casos_doencas".
 *
 * @property integer $id
 * @property integer $cliente_id
 * @property integer $doenca_id
 * @property integer $inserido_por
 * @property string $data_cadastro
 * @property integer $atualizado_por
 * @property string $data_atualizacao
 * @property string $coordenadas_area
 * @property integer $bairro_quarteirao_id
 * @property string $nome_paciente
 * @property string $data_sintomas
 *
 * @property BairroQuarteiroes $bairroQuarteirao
 * @property Clientes $cliente
 * @property Doencas $doenca
 * @property Usuarios $inseridoPor
 * @property Usuarios $atualizadoPor
 */
class CasoDoenca extends ClienteActiveRecord
{
    public $latitude;
    public $longitude;
    public $coordenadasJson;

    public static function tableName()
    {
        return 'casos_doencas';
    }

    public function rules()
    {
        return [
            [['cliente_id', 'doenca_id', 'inserido_por', 'bairro_id'], 'required'],
            [['cliente_id', 'doenca_id', 'inserido_por', 'atualizado_por', 'bairro_quarteirao_id', 'bairro_id'], 'integer'],
            [['data_cadastro', 'coordenadasJson', 'data_atualizacao', 'data_sintomas'], 'safe'],
            [['coordenadas_area', 'nome_paciente'], 'string'],
            [['latitude', 'longitude'], 'required', 'on' => ['insert','update']],
            [['latitude', 'longitude'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cliente_id' => 'Cliente',
            'doenca_id' => 'Doença',
            'inserido_por' => 'Inserido Por',
            'data_cadastro' => 'Data Cadastro',
            'atualizado_por' => 'Atualizado Por',
            'data_atualizacao' => 'Data Atualização',
            'coordenadas_area' => 'Coordenadas Area',
            'bairro_quarteirao_id' => 'Quarteirão',
            'nome_paciente' => 'Nome Paciente',
            'data_sintomas' => 'Data Sintomas',
            'bairro_id' => 'Bairro',
        ];
    }

    public function beforeSave($insert)
    {
        $this->_setQuarteirao();

        return parent::beforeSave($insert);
    }

    public function getBairroQuarteirao()
    {
        return $this->hasOne(BairroQuarteirao::className(), ['id' => 'bairro_quarteirao_id']);
    }

    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
    }

    public function getDoenca()
    {
        return $this->hasOne(Doenca::className(), ['id' => 'doenca_id']);
    }

    public function getBairro()
    {
        return $this->hasOne(Bairro::className(), ['id' => 'bairro_id']);
    }

    public function getInseridoPor()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'inserido_por']);
    }

    public function getAtualizadoPor()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'atualizado_por']);
    }

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

    private function _validateAndLoadPostgisField()
        {
            if(!$this->coordenadasJson) {
                return true;
            }

            $this->coordenadas_area = new \yii\db\Expression($this->arrayToWkt('Point', explode(',',$this->coordenadasJson)));
            return true;
        }
}
