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
            ['nome', 'unique', 'compositeWith' => 'municipio_id'],
            [['municipio_id', 'bairro_tipo_id'], 'integer'],
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
            'bairro_tipo_id' => 'Categoria de Bairro',
        );
    }
}
