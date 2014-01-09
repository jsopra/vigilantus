<?php

namespace app\models;

use app\components\ActiveRecord;
use yii\db\Expression;

/**
 * Este é a classe de modelo da tabela "bairro_tipos".
 *
 * Estas são as colunas disponíveis na tabela 'bairro_tipos':
 * @property integer $id
 * @property integer $municipio_id
 * @property string $nome
 * @property string $data_cadastro
 * @property string $data_atualizacao
 * @property integer $inserido_por
 * @property integer $atualizado_por
 */
class BairroTipo extends ActiveRecord
{

    /**
     * @return string nome da tabela do banco de dados
     */
    public static function tableName()
    {
        return 'bairro_tipos';
    }

    public function beforeDelete()
    {

        if ($this->qtdeBairros > 0) {
            $this->addError('id', 'O tipo tem bairros vinculados');
            return false;
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
            array('data_cadastro', 'default', 'value' => new Expression('NOW()'), 'on' => 'insert'),
            array('data_atualizacao', 'default', 'value' => new Expression('NOW()'), 'on' => 'update'),
            array('atualizado_por', 'required', 'on' => 'update'),
            array(['municipio_id', 'nome'], 'unique'),
            array('data_atualizacao', 'safe'),
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
            'inseridoPor' => array(self::BELONGS_TO, 'Usuario', 'inserido_por'),
            'atualizadoPor' => array(self::BELONGS_TO, 'Usuario', 'atualizado_por'),
            'bairros' => array(self::HAS_MANY, 'Bairro', 'bairro_tipo_id'),
            'qtdeBairros' => array(self::STAT, 'Bairro', 'bairro_tipo_id'),
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
            'data_cadastro' => 'Data Cadastro',
            'data_atualizacao' => 'Data Atualização',
            'inserido_por' => 'Inserido Por',
            'atualizado_por' => 'Atualizado Por',
        );
    }
}
