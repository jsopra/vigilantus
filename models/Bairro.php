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
            array(['municipio_id', 'nome'], 'required'),
            array('nome', 'unique', 'compositeWith' => 'municipio_id'),
            array(['municipio_id', 'bairro_tipo_id'], 'integer'),
        );
    }

    /**
     * @return array regras de relações
     */
    public function relations()
    {
        // AVISO: você talvez tenha de ajustar o nome da relação gerada.
        return array(
            'municipio' => array(self::BELONGS_TO, 'Municipio', 'municipio_id'),
            'bairroTipo' => array(self::BELONGS_TO, 'BairroTipo', 'bairro_tipo_id'),
        );
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
            'bairro_tipo_id' => 'Tipo de Bairro',
        );
    }
}
