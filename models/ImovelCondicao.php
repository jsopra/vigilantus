<?php

namespace app\models;

use app\components\ActiveRecord;

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
            array('atualizado_por', 'required', 'on' => 'update'),
            array('inserido_por', 'required', 'on' => 'insert'),
            array('nome', 'unique', 'compositeWith' => 'municipio_id'),
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
            'exibe_nome' => 'Exibe Nome',
            'data_cadastro' => 'Data Cadastro',
            'data_atualizacao' => 'Data Atualização',
            'inserido_por' => 'Inserido Por',
            'atualizado_por' => 'Atualizado Por',
        );
    }
}
