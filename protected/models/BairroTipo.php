<?php

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
 *
 * Estas são as relações do modelo disponíveis:
 * @property Municipios $municipio
 * @property Usuarios $inseridoPor
 * @property Usuarios $atualizadoPor
 */
class BairroTipo extends PMunicipioActiveRecord
{
	/**
	 * Retorna o modelo estático da classe de AR especificada
	 * @return BairroTipo the static model class
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
		return 'bairro_tipos';
	}
    
    public function beforeDelete() {
        
        if($this->qtdeBairros > 0) {
            $this->addError('id', Yii::t('BairroTipo', 'O tipo tem bairros vinculados'));
            return false;
        }
        
        return parent::beforeDelete();
    }

	/**
	 * @return array regras de validação para os atributos do modelo
	 */
	public function rules()
	{
		// AVISO: só defina regras dos atributos que receberão dados do usuário
		return array(
			array('municipio_id, nome, inserido_por', 'required'),
			array('municipio_id, inserido_por, atualizado_por', 'numerical', 'integerOnly'=>true),
            array('data_cadastro', 'default', 'value' => new CDbExpression('NOW()'), 'on' => 'insert'),
            array('data_atualizacao', 'default', 'value' => new CDbExpression('NOW()'), 'on' => 'update'),
            array('atualizado_por', 'required', 'on' => 'update'),
            array('municipio_id+nome','uniqueMultiColumnValidator', 'caseSensitive' => true),
			array('data_atualizacao', 'safe'),
			// Esta regra é usada pelo método search().
			// Remova os atributos que não deveriam ser pesquisáveis.
			array('id, municipio_id, nome, data_cadastro, data_atualizacao, inserido_por, atualizado_por', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('BairroTipo', 'ID'),
			'municipio_id' => Yii::t('BairroTipo', 'Município'),
			'nome' => Yii::t('BairroTipo', 'Nome'),
			'data_cadastro' => Yii::t('BairroTipo', 'Data Cadastro'),
			'data_atualizacao' => Yii::t('BairroTipo', 'Data Atualização'),
			'inserido_por' => Yii::t('BairroTipo', 'Inserido Por'),
			'atualizado_por' => Yii::t('BairroTipo', 'Atualizado Por'),
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
		$criteria->compare('data_cadastro',$this->data_cadastro,true);
		$criteria->compare('data_atualizacao',$this->data_atualizacao,true);
		$criteria->compare('inserido_por',$this->inserido_por);
		$criteria->compare('atualizado_por',$this->atualizado_por);

		return new CActiveDataProvider(get_class($this), array(
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
			
			// Implemente aqui as exclusões mais complexas
			$return = parent::delete();

			if ($return)
				$transaction->commit();
			else
				$transaction->rollback();
		}
		catch(Exception $e) {

			$transaction->rollback();

			throw $e;
		}

		return $return;
	}
}
