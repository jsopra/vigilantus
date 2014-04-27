<?php

namespace app\models;

use app\components\ActiveRecord;
use yii\db\Expression;

/**
 * Este é a classe de modelo da tabela "especies_transmissores".
 *
 * Estas são as colunas disponíveis na tabela 'especies_transmissores':
 * @property integer $id
 * @property integer $municipio_id
 * @property string $nome
 */
class EspecieTransmissor extends ActiveRecord
{
    /**
     * @return string nome da tabela do banco de dados
     */
    public static function tableName()
    {
        return 'especies_transmissores';
    }

    /**
     * @return array regras de validação para os atributos do modelo
     */
    public function rules()
    {
        return [
            [['municipio_id', 'nome'], 'required'],
            ['municipio_id', 'exist', 'targetClass' => Municipio::className(), 'targetAttribute' => 'id'],
            ['nome', 'unique', 'compositeWith' => 'municipio_id'],
        ];
    }
    
    /**
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
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
        );
    }
}
