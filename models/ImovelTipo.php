<?php

namespace app\models;

use app\components\ActiveRecord;
use yii\db\Expression;

/**
 * Este é a classe de modelo da tabela "imovel_tipos".
 *
 * Estas são as colunas disponíveis na tabela 'imovel_tipos':
 * @property integer $id
 * @property integer $municipio_id
 * @property string $nome
 * @property string $sigla
 * @property string $data_cadastro
 * @property string $data_atualizacao
 * @property integer $inserido_por
 * @property integer $atualizado_por
 * @property boolean $excluido
 * @property integer $excluido_por
 * @property string $data_exclusao
 */
class ImovelTipo extends ActiveRecord
{

    /**
     * @return string nome da tabela do banco de dados
     */
    public static function tableName()
    {
        return 'imovel_tipos';
    }

    /**
     * @return array regras de validação para os atributos do modelo
     */
    public function rules()
    {
        return array(
            array(['municipio_id', 'nome', 'inserido_por'], 'required'),
            array(['municipio_id', 'inserido_por', 'atualizado_por', 'excluido_por'], 'integer'),
            array(['sigla', 'data_atualizacao', 'excluido', 'data_exclusao'], 'safe'),
            array('id', 'uniqueImovelTipo', 'on' => 'insert, update'),
            array('data_cadastro', 'default', 'value' => new Expression('NOW()'), 'on' => 'insert'),
            array('data_atualizacao', 'default', 'value' => new Expression('NOW()'), 'on' => 'update'),
            array('data_exclusao', 'default', 'value' => new Expression('NOW()'), 'on' => 'remove'),
            array('inserido_por', 'required', 'on' => 'insert'),
            array('atualizado_por', 'required', 'on' => 'update'),
            array('excluido_por', 'required', 'on' => 'remove'),
        );
    }

    public function uniqueImovelTipo($attribute, $params)
    {
        if ($this->scenario == 'remove') {
            return;
        }
            
        $criteria = new CDbCriteria;

        if (!$this->isNewRecord) {
            $criteria->addCondition('t.id <> :id');
            $criteria->params[':id'] = $this->id;
        }

        if ($this->nome) {
            $criteria->addCondition('LOWER(t.nome) = LOWER(:nome)');
            $criteria->params[':nome'] = $this->nome;
        }

        if ($this->sigla) {
            $criteria->addCondition('LOWER(t.sigla) = LOWER(:sigla)');
            $criteria->params[':sigla'] = $this->sigla;
        }

        if ($this->municipio_id) {
            $criteria->addCondition('t.municipio_id = :municipio');
            $criteria->params[':municipio'] = $this->municipio_id;
        }

        $objects = $this->findAll($criteria);

        if (count($objects) > 0) {
            $this->addError('id', 'Registro já existe');
        }
    }

    /**
     * @param ActiveQuery $query
     */
    public static function ativo($query)
    {
        $query->andWhere('excluido IS FALSE');
    }
    
    /**
     * @param ActiveQuery $query
     */
    public static function excluido($query)
    {
        $query->andWhere('excluido IS TRUE');
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
     * @return Usuario
     */
    public function getExcluidoPor()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'excluido_por']);
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
            'sigla' => 'Sigla',
            'data_cadastro' => 'Data Cadastro',
            'data_atualizacao' => 'Data Atualização',
            'inserido_por' => 'Inserido Por',
            'atualizado_por' => 'Atualizado Por',
            'excluido' => 'Excluído',
            'excluido_por' => 'Excluído Por',
            'data_exclusao' => 'Data Exclusão',
        );
    }

    /**
     * Exclui a linha da tabela correspondente a este active record.
     * @return boolean se a exclusão foi feita com sucesso ou não.
     */
    public function delete()
    {
        $this->scenario = 'remove';
        $this->excluido = true;
        return $this->save();
    }
}
