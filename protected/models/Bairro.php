<?php

/**
 * Este é a classe de modelo da tabela "bairros".
 *
 * Estas são as colunas disponíveis na tabela 'bairros':
 * @property integer $id
 * @property integer $municipio_id
 * @property string $nome
 * @property integer $bairro_tipo_id
 *
 * Estas são as relações do modelo disponíveis:
 * @property Municipios $municipio
 * @property BairroTipos $bairroTipo
 */
class Bairro extends PMunicipioActiveRecord
{
	/**
	 * Retorna o modelo estático da classe de AR especificada
	 * @return Bairro the static model class
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
		return 'bairros';
	}

	/**
	 * @return array regras de validação para os atributos do modelo
	 */
	public function rules()
	{
		// AVISO: só defina regras dos atributos que receberão dados do usuário
		return array(
			array('municipio_id, nome', 'required'),
            array('municipio_id+nome+bairro_tipo_id','uniqueMultiColumnValidator', 'caseSensitive' => true),
			array('municipio_id, bairro_tipo_id', 'numerical', 'integerOnly'=>true),
			// Esta regra é usada pelo método search().
			// Remova os atributos que não deveriam ser pesquisáveis.
			array('id, municipio_id, nome, bairro_tipo_id', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('Bairro', 'ID'),
			'municipio_id' => Yii::t('Bairro', 'Município'),
			'nome' => Yii::t('Bairro', 'Nome'),
			'bairro_tipo_id' => Yii::t('Bairro', 'Tipo de Bairro'),
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
		$criteria->compare('bairro_tipo_id',$this->bairro_tipo_id);

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
