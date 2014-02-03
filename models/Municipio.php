<?php

namespace app\models;

use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "municipios".
 *
 * Estas são as colunas disponíveis na tabela 'municipios':
 * @property integer $id
 * @property string $nome
 * @property string $sigla_estado
 * @property string $nome_contato
 * @property string $email_contato
 * @property string $telefone_contato
 * @property string $departamento
 * @property string $cargo
 */
class Municipio extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'municipios';
    }

    /**
     * @return array regras de validação para os atributos do modelo
     */
    public function rules()
    {
        // AVISO: só defina regras dos atributos que receberão dados do usuário
        return array(
            array('nome, sigla_estado, nome_contato, telefone_contato, departamento', 'required'),
            array('sigla_estado', 'length', 'max' => 2),
            array('email_contato, cargo', 'safe'),
            // Esta regra é usada pelo método search().
            // Remova os atributos que não deveriam ser pesquisáveis.
            array('id, nome, sigla_estado, nome_contato, email_contato, telefone_contato, departamento, cargo', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array regras de relações
     */
    public function relations()
    {
        // AVISO: você talvez tenha de ajustar o nome da relação gerada.
        return array(
            'usuarios' => array(self::HAS_MANY, 'Usuarios', 'municipio_id'),
        );
    }

    /**
     * @return array descrição dos atributos (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('Municipio', 'ID'),
            'nome' => Yii::t('Municipio', 'Nome'),
            'sigla_estado' => Yii::t('Municipio', 'Estado Sigla'),
            'nome_contato' => Yii::t('Municipio', 'Nome do contato'),
            'email_contato' => Yii::t('Municipio', 'Email do contato'),
            'telefone_contato' => Yii::t('Municipio', 'Telefone do contato'),
            'departamento' => Yii::t('Municipio', 'Departamento do contato'),
            'cargo' => Yii::t('Municipio', 'Cargo do contato'),
        );
    }

    /**
     * Retorna uma lista de modelos baseada nas condições de filtro/busca atuais
     * @return CActiveDataProvider o data provider que pode retornar os dados.
     */
    public function search()
    {
        // Aviso: Remove do código a seguir os atributos que não deveriam ser
        // pesquisados pelo usuário.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('nome', $this->nome, true);
        $criteria->compare('sigla_estado', $this->sigla_estado, true);
        $criteria->compare('nome_contato', $this->nome_contato, true);
        $criteria->compare('email_contato', $this->email_contato, true);
        $criteria->compare('telefone_contato', $this->telefone_contato, true);
        $criteria->compare('departamento', $this->departamento, true);
        $criteria->compare('cargo', $this->cargo, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Exclui a linha da tabela correspondente a este active record.
     * @return boolean se a exclusão foi feita com sucesso ou não.
     * @throws CException se o registro for novo
     */
    public function delete()
    {
        throw new \Exception(\Yii::t('Site', 'Exclusão não habilitada'), 500);
    }
    
    /**
     * Busca municípios
     * @param int $id Default is null
     * @return Municipio[] 
     */
    public static function getMunicipios($id = null) {
        
        $query = self::find();

        if($id)
            $query->andWhere(['"id"' => $id]);
        
        return $query->all();
    }
}
