<?php

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
 *
 * Estas são as relações do modelo disponíveis:
 * @property Municipios $municipio
 * @property Usuarios $inseridoPor
 * @property Usuarios $atualizadoPor
 * @property Usuarios $excluidoPor
 */
class ImovelTipo extends PMunicipioActiveRecord
{
	/**
	 * Retorna o modelo estático da classe de AR especificada
	 * @return ImovelTipos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string nome da tabela do banco de dados
	 */
	public function tableName()
	{
		return 'imovel_tipos';
	}

	/**
	 * @return array regras de validação para os atributos do modelo
	 */
	public function rules()
	{
		// AVISO: só defina regras dos atributos que receberão dados do usuário
		return array(
			array('municipio_id, nome, inserido_por', 'required'),
			array('municipio_id, inserido_por, atualizado_por, excluido_por', 'numerical', 'integerOnly'=>true),
			array('sigla, data_atualizacao, excluido, data_exclusao', 'safe'),
            array('id','uniqueImovelTipo', 'on' => 'insert, update'),
            array('data_cadastro', 'default', 'value' => new CDbExpression('NOW()'), 'on' => 'insert'),
            array('data_atualizacao', 'default', 'value' => new CDbExpression('NOW()'), 'on' => 'update'),
            array('data_exclusao', 'default', 'value' => new CDbExpression('NOW()'), 'on' => 'remove'),
            array('atualizado_por', 'required', 'on' => 'update'),
            array('excluido_por', 'required', 'on' => 'remove'),
			// Esta regra é usada pelo método search().
			// Remova os atributos que não deveriam ser pesquisáveis.
			array('id, municipio_id, nome, sigla, data_cadastro, data_atualizacao, inserido_por, atualizado_por, excluido, excluido_por, data_exclusao', 'safe', 'on'=>'search'),
		);
	}
    
    public function uniqueImovelTipo($attribute, $params) {
        
        if($this->scenario == 'remove')
            return;
        
        $criteria = new CDbCriteria;
        
        if(!$this->isNewRecord) {
            $criteria->addCondition('t.id <> :id');
            $criteria->params[':id'] = $this->id;
        }
        
        if($this->nome) {
            $criteria->addCondition('LOWER(t.nome) = LOWER(:nome)');
            $criteria->params[':nome'] = $this->nome;
        }
        
        if($this->sigla) {
            $criteria->addCondition('LOWER(t.sigla) = LOWER(:sigla)');
            $criteria->params[':sigla'] = $this->sigla;
        }
        
        if($this->municipio_id) {
            $criteria->addCondition('t.municipio_id = :municipio');
            $criteria->params[':municipio'] = $this->municipio_id;
        }
             
        $objects = $this->findAll($criteria);

        if(count($objects) > 0)
             $this->addError('id',Yii::t('Site', 'Registro já existe'));
        
    }
    
    public function scopes() {
        
        return array(
            'ativo' => array(
                'condition' => 'excluido IS FALSE',
            ),
            'excluido' => array(
                'condition' => 'excluido IS TRUE',
            ),
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
			'excluidoPor' => array(self::BELONGS_TO, 'Usuario', 'excluido_por'),
		);
	}

	/**
	 * @return array descrição dos atributos (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('ImovelTipos', 'ID'),
			'municipio_id' => Yii::t('ImovelTipos', 'Município'),
			'nome' => Yii::t('ImovelTipos', 'Nome'),
			'sigla' => Yii::t('ImovelTipos', 'Sigla'),
			'data_cadastro' => Yii::t('ImovelTipos', 'Data Cadastro'),
			'data_atualizacao' => Yii::t('ImovelTipos', 'Data Atualização'),
			'inserido_por' => Yii::t('ImovelTipos', 'Inserido Por'),
			'atualizado_por' => Yii::t('ImovelTipos', 'Atualizado Por'),
			'excluido' => Yii::t('ImovelTipos', 'Excluído'),
			'excluido_por' => Yii::t('ImovelTipos', 'Excluído Por'),
			'data_exclusao' => Yii::t('ImovelTipos', 'Data Exclusão'),
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('municipio_id',$this->municipio_id);
		$criteria->compare('nome',$this->nome,true);
		$criteria->compare('sigla',$this->sigla,true);
		$criteria->compare('data_cadastro',$this->data_cadastro,true);
		$criteria->compare('data_atualizacao',$this->data_atualizacao,true);
		$criteria->compare('inserido_por',$this->inserido_por);
		$criteria->compare('atualizado_por',$this->atualizado_por);
		$criteria->compare('excluido',$this->excluido);
		$criteria->compare('excluido_por',$this->excluido_por);
		$criteria->compare('data_exclusao',$this->data_exclusao,true);

		return new PMunicipioActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Exclui a linha da tabela correspondente a este active record.
	 * @return boolean se a exclusão foi feita com sucesso ou não.
	 * @throws CException se o registro for novo
	 */
	public function delete()
	{
		$transaction = $this->getDbConnection()->beginTransaction();

		try {
			
            $this->scenario = 'remove';
            
			$this->excluido = true;

			if ($this->save()) {
				$transaction->commit();
                return true;
            }
			else
				$transaction->rollback();
		}
		catch(Exception $e) {

			$transaction->rollback();

			throw $e;
		}

		return false;
	}
}
