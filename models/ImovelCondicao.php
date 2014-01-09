<?php

namespace app\models;

use app\components\ActiveRecord;
use yii\db\Expression;

/**
 * Este é a classe de modelo da tabela "imovel_condicoes".
 *
 * Estas são as colunas disponíveis na tabela 'imovel_condicoes':
 * @property integer $id
 * @property integer $municipio_id
 * @property string $nome
 * @property boolean $exibe_nome
 * @property string $data_cadastro
 * @property string $data_atualizacao
 * @property integer $inserido_por
 * @property integer $atualizado_por
 */
class ImovelCondicao extends ActiveRecord
{
    /**
     * @return string nome da tabela do banco de dados
     */
    public static function tableName()
    {
        return 'imovel_condicoes';
    }

    /**
     * @return array regras de validação para os atributos do modelo
     */
    public function rules()
    {
        return array(
            array(['municipio_id', 'nome', 'inserido_por'], 'required'),
            array(['municipio_id', 'inserido_por', 'atualizado_por'], 'integer'),
            array('exibe_nome', 'default', 'value' => false),
            array('data_cadastro', 'default', 'value' => new Expression('NOW()'), 'on' => 'insert'),
            array('data_atualizacao', 'default', 'value' => new Expression('NOW()'), 'on' => 'update'),
            array('atualizado_por', 'required', 'on' => 'update'),
            array('inserido_por', 'required', 'on' => 'insert'),
            array(['municipio_id', 'nome'], 'unique'),
        );
    }

    /**
     * @return array regras de relações
     */
    public function relations()
    {
        return array(
            'municipio' => array(self::BELONGS_TO, 'Municipio', 'municipio_id'),
            'inseridoPor' => array(self::BELONGS_TO, 'Usuario', 'inserido_por'),
            'atualizadoPor' => array(self::BELONGS_TO, 'Usuario', 'atualizado_por'),
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
            'exibe_nome' => 'Exibe Nome',
            'data_cadastro' => 'Data Cadastro',
            'data_atualizacao' => 'Data Atualização',
            'inserido_por' => 'Inserido Por',
            'atualizado_por' => 'Atualizado Por',
        );
    }
}
