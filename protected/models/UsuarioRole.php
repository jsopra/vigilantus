<?php

/**
 * Este é a classe de modelo da tabela "usuario_roles".
 *
 * Estas são as colunas disponíveis na tabela 'usuario_roles':
 * @property integer $id
 * @property string $nome
 *
 * Estas são as relações do modelo disponíveis:
 * @property Usuarios[] $usuarioses
 */
class UsuarioRole extends PActiveRecord
{
    const ROOT = 1;
    const ADMINISTRADOR = 2;
    const GERENTE = 3;
    const USUARIO = 4;
    
	/**
	 * Retorna o modelo estático da classe de AR especificada
	 * @return UsuarioRole the static model class
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
		return 'usuario_roles';
	}

	/**
	 * @return array regras de validação para os atributos do modelo
	 */
	public function rules()
	{
		// AVISO: só defina regras dos atributos que receberão dados do usuário
		return array(
			array('nome', 'required'),
			// Esta regra é usada pelo método search().
			// Remova os atributos que não deveriam ser pesquisáveis.
			array('id, nome', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array regras de relações
	 */
	public function relations()
	{
		// AVISO: você talvez tenha de ajustar o nome da relação gerada.
		return array(
			'usuarios' => array(self::HAS_MANY, 'Usuarios', 'usuario_role_id'),
		);
	}

	/**
	 * @return array descrição dos atributos (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('UsuarioRole', 'ID'),
			'nome' => Yii::t('UsuarioRole', 'Nome'),
		);
	}
    
    public function doNivelDoUsuario(Usuario $usuario) {
        
		if($usuario->role->id == self::ADMINISTRADOR)
			$this->getDbCriteria()->mergeWith(array('condition' => 'id <> ' . self::ROOT));
        
        return $this;
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
		$criteria->compare('nome',$this->nome,true);

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
