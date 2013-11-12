<?php

/**
 * Este é a classe de modelo da tabela "usuarios".
 *
 * Estas são as colunas disponíveis na tabela 'usuarios':
 * @property integer $id
 * @property string $nome
 * @property string $login
 * @property string $senha
 * @property string $sal
 * @property integer $municipio_id
 * @property integer $usuario_role_id
 * @property string $ultimo_login
 * @property string $email
 * @property string $token_recupera_senha
 * @property string $data_recupera_senha
 * @property boolean $excluido
 *
 * Estas são as relações do modelo disponíveis:
 * @property Municipios $municipio
 * @property UsuarioRoles $usuarioRole
 */
class Usuario extends PActiveRecord
{
    public $senha2;
    
    protected $_trackChangedAttributes = true;
    
	/**
	 * Retorna o modelo estático da classe de AR especificada
	 * @return Usuario the static model class
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
		return 'usuarios';
	}

	/**
	 * @return array regras de validação para os atributos do modelo
	 */
	public function rules()
	{
		// AVISO: só defina regras dos atributos que receberão dados do usuário
		return array(
			array('nome, login, sal, usuario_role_id, email', 'required'),
			array('municipio_id, usuario_role_id', 'numerical', 'integerOnly'=>true),
			array('ultimo_login, token_recupera_senha, data_recupera_senha, excluido', 'safe'),
            array('login','unique', 'message' => Yii::t('Site', 'Este usuário já existe')),
            array('email','unique', 'message' => Yii::t('Site', 'Este email já existe')),
            array('email','email'),
            array('senha', 'length', 'min' => 8),
			array('senha, senha2', 'required', 'on' => 'insert, updatePassword'),
            array('senha', 'compare', 'compareAttribute'=>'senha2', 'on' => 'insert, updatePassword'),
            // Esta regra é usada pelo método search().
			// Remova os atributos que não deveriam ser pesquisáveis.
			array('id, nome, login, senha, sal, municipio_id, usuario_role_id, ultimo_login, email, token_recupera_senha, data_recupera_senha, excluido', 'safe', 'on'=>'search'),
		);
	}
    
    public function scopes() {
        
        return array(
            'ativo' => array(
                'condition' => 'excluido IS FALSE',
            )  
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
			'role' => array(self::BELONGS_TO, 'UsuarioRole', 'usuario_role_id'),
		);
	}
    
    public function doNivelDoUsuario(Usuario $usuario) {
        
        if($usuario->municipio_id)
			$this->getDbCriteria()->mergeWith(array('condition' => 'municipio_id = ' . $usuario->municipio_id));
        
        
		if($usuario->role->id == UsuarioRole::ADMINISTRADOR)
			$this->getDbCriteria()->mergeWith(array('condition' => 'usuario_role_id <> ' . UsuarioRole::ROOT));
        
        return $this;
    }
    
    public function doEmail($email) {

		$this->getDbCriteria()->mergeWith(array(
			'condition' => "email = :email",
			'params' => array(':email' => $email)
		));
		
		return $this;
	}
    
    public function beforeValidate() {
		
		if($this->getScenario() == 'insert')
			$this->sal = uniqid();
		
		return parent::beforeValidate();
	}
    
    public function afterValidate() {
        
        $return = parent::afterValidate();
        
        if($this->errors)
            return $return;
            
        if($this->usuario_role_id != UsuarioRole::ROOT && !$this->municipio_id)
            $this->addError('municipio_id', Yii::t('Usuario', 'Município não está definido'));
        
        return $return;
    }
    
    public function beforeSave() {
		
		$return = parent::beforeSave();
		
		switch ($this->getScenario()) {
			
			case 'insert' :
				$this->senha = $this->senha ? $this->getPassword() : null;
				break;
			
			case 'update' : 

				$this->senha = $this->senha ? $this->getPassword() : $this->attributeOriginalValue('senha');
				break;
			
			case 'updatePassword' : 
				$this->senha = $this->getPassword();
				break;
			
			default:
				break;
		}
		
		return $return;
	}

	/**
	 * @return array descrição dos atributos (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('Usuario', 'ID'),
			'nome' => Yii::t('Usuario', 'Nome'),
			'login' => Yii::t('Usuario', 'Login'),
			'senha' => Yii::t('Usuario', 'Senha'),
            'senha2' => Yii::t('Usuario', 'Repetição da senha'),
			'sal' => Yii::t('Usuario', 'Sal'),
			'municipio_id' => Yii::t('Usuario', 'Município'),
			'usuario_role_id' => Yii::t('Usuario', 'Nível do Usuário'),
			'ultimo_login' => Yii::t('Usuario', 'Último Login'),
			'email' => Yii::t('Usuario', 'Email'),
			'token_recupera_senha' => Yii::t('Usuario', 'Token de Recuperação de Senha'),
			'data_recupera_senha' => Yii::t('Usuario', 'Data de Recuperação de Senha'),
			'excluido' => Yii::t('Usuario', 'Excluído'),
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
		$criteria->compare('nome',$this->nome,true);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('senha',$this->senha,true);
		$criteria->compare('sal',$this->sal,true);
		$criteria->compare('municipio_id',$this->municipio_id);
		$criteria->compare('usuario_role_id',$this->usuario_role_id);
		$criteria->compare('ultimo_login',$this->ultimo_login,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('token_recupera_senha',$this->token_recupera_senha,true);
		$criteria->compare('data_recupera_senha',$this->data_recupera_senha,true);
		$criteria->compare('excluido',$this->excluido);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    /**
     * Codificação de senha
     * 
     * @param string $sal
     * @param string $senha
     * @return string 
     */
    public static function encryptPassword($sal,$password) 
    {   
        return md5($password . $sal);
    }
    
    /**
	 * Busca senha criptografada
	 * @param string $senha Default get user password
	 * @return string Senha criptografada considerando o sal do usuario
	 */
	public function getPassword($senha = null) {
		
		$senha = $senha ? $senha : $this->senha;
		
		return self::encryptPassword($this->sal, $senha);
	}
    
    /**
	 * Autentica usuário
	 * @param string $login
	 * @param string $senha
	 * @return boolean
	 */
	public function validatePassword($senha) {
	
		$senhaCriptografada = $this->encryptPassword($this->sal,$senha) ;
		
		Yii::log("Senha: " . $senhaCriptografada . " com o sal " . $this->sal, 'info', 'application.models.Login');
  
		if ($senhaCriptografada !== $this->senha)
			return false;
		
		return true; 
	}
    
    /**
     * Altera a senha
     * @param type $password 
     */
    public function changePassword($password, $confirmation)
    {
        if (!$this->sal) {
            
            if ($this->isNewRecord) {
                $this->sal = uniqid();
            }
            else {
                // Se ele dá um find sem trazer a coluna sal, não deve recriar o sal - ele já existe!!!
                throw new Exception('Você deve construir o objeto trazendo o SAL para poder alterar a SENHA');
            }
        }
        
        $password = Usuario::encryptPassword($this->sal, $password);
        $confirmation = Usuario::encryptPassword($this->sal, $confirmation);
        
        if ($password != $confirmation) {
            return $this->addError('senha', 'A confirmação de senha não confere');
        }
        
        $this->senha = $password;
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
			
			$this->scenario = 'exclusao';
			$this->excluido = true;
			$return = $this->save();
			
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
