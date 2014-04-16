<?php

namespace app\models;

use app\components\ActiveRecord;
use yii\db\Expression;

/**
 * Este é a classe de modelo da tabela "bairro_categorias".
 *
 * Estas são as colunas disponíveis na tabela 'bairro_categorias':
 * @property integer $id
 * @property integer $municipio_id
 * @property string $nome
 * @property string $data_cadastro
 * @property string $data_atualizacao
 * @property integer $inserido_por
 * @property integer $atualizado_por
 */
class BairroCategoria extends ActiveRecord
{
    /**
     * @return string nome da tabela do banco de dados
     */
    public static function tableName()
    {
        return 'bairro_categorias';
    }

    public function beforeDelete()
    {
        if ($this->getBairros()->count() > 0) {
            throw new \Exception('O tipo tem bairros vinculados');
        }

        return parent::beforeDelete();
    }

    /**
     * @return array regras de validação para os atributos do modelo
     */
    public function rules()
    {
        return array(
            array(['municipio_id', 'nome', 'inserido_por'], 'required'),
            array(['municipio_id', 'inserido_por', 'atualizado_por'], 'integer'),
            array('atualizado_por', 'required', 'on' => 'update'),
            array('nome', 'unique', 'compositeWith' => 'municipio_id'),
            array('data_atualizacao', 'safe'),
        );
    }
    
    /**
     * @return Bairro[]
     */
    public function getBairros()
    {
        return $this->hasMany(Bairro::className(), ['bairro_categoria_id' => 'id']);
    }
    
    /**
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
    }
    
    /**
     * @return Usuario
     */
    public function getInseridoPor()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'inserido_por']);
    }
    
    /**
     * @return Usuario
     */
    public function getAtualizadoPor()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'atualizado_por']);
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
            'data_cadastro' => 'Data Cadastro',
            'data_atualizacao' => 'Data Atualização',
            'inserido_por' => 'Inserido Por',
            'atualizado_por' => 'Atualizado Por',
        );
    }
}
