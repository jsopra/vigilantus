<?php

namespace app\models;

use Yii;

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
class CasoDoenca extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'casos_doencas';
    }

    public function rules()
    {
        return [
            [['cliente_id', 'doenca_id', 'inserido_por'], 'required'],
            [['cliente_id', 'doenca_id', 'inserido_por', 'atualizado_por', 'bairro_quarteirao_id'], 'integer'],
            [['data_cadastro', 'data_atualizacao', 'data_sintomas'], 'safe'],
            [['coordenadas_area', 'nome_paciente'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cliente_id' => 'Cliente ID',
            'doenca_id' => 'Doenca ID',
            'inserido_por' => 'Inserido Por',
            'data_cadastro' => 'Data Cadastro',
            'atualizado_por' => 'Atualizado Por',
            'data_atualizacao' => 'Data Atualizacao',
            'coordenadas_area' => 'Coordenadas Area',
            'bairro_quarteirao_id' => 'Bairro Quarteirao ID',
            'nome_paciente' => 'Nome Paciente',
            'data_sintomas' => 'Data Sintomas',
        ];
    }

    public function getBairroQuarteirao()
    {
        return $this->hasOne(BairroQuarteiroes::className(), ['id' => 'bairro_quarteirao_id']);
    }

    public function getCliente()
    {
        return $this->hasOne(Clientes::className(), ['id' => 'cliente_id']);
    }

    public function getDoenca()
    {
        return $this->hasOne(Doencas::className(), ['id' => 'doenca_id']);
    }

    public function getInseridoPor()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'inserido_por']);
    }

    public function getAtualizadoPor()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'atualizado_por']);
    }
}
