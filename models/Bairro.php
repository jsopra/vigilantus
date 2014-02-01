<?php

namespace app\models;

use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "bairros".
 *
 * Estas são as colunas disponíveis na tabela 'bairros':
 * @property integer $id
 * @property integer $municipio_id
 * @property string $nome
 * @property integer $bairro_tipo_id
 * @property integer $ultimo_mes_rg
 * @property integer $ultimo_ano_rg
 */
class Bairro extends ActiveRecord
{
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
            [['municipio_id', 'bairro_tipo_id', 'ultimo_mes_rg', 'ultimo_ano_rg'], 'integer'],
        );
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
    public function getTipo()
    {
        return $this->hasOne(BairroCategoria::className(), ['id' => 'bairro_tipo_id']);
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
            'bairro_tipo_id' => 'Categoria de Bairro',
            'ultimo_mes_rg' => 'Último Mês com informações de RG',
            'ultimo_mes_rg' => 'Último Ano com informações de RG'
        );
    }
}
