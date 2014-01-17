<?php

namespace app\models;

use app\components\ActiveRecord;
use app\models\Municipio;
use app\models\UsuarioRole;
use yii\validators\Validator;
use yii\web\IdentityInterface;

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
 */
class Usuario extends ActiveRecord implements IdentityInterface
{
    /* Métodos pra interface IdentityInterface */
    
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->sal;
    }
    
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::find($id);
    }
    
    /* Métodos ActiveRecord */
    
    public $senha2;

    /**
     * @return string nome da tabela do banco de dados
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * @return array regras de validação para os atributos do modelo
     */
    public function rules()
    {
        $requiredAttributes = ['nome', 'login', '!sal', 'usuario_role_id', 'email'];
        
        if ($this->isNewRecord) {
            $requiredAttributes[] = 'senha';
            $requiredAttributes[] = 'senha2';
        }
        
        return array(
            array($requiredAttributes, 'required'),
            array(['municipio_id', 'usuario_role_id'], 'integer'),
            array('login', 'unique'),
            array('email', 'unique'),
            array('email', 'email'),
            array('senha', 'verificarSenha'),
            array(['senha', 'senha2'], 'safe'),
        );
    }
    
    public function verificarSenha($attribute)
    {
        if ($this->isNewRecord || $this->senha != null) {
            
            $requiredValidator = Validator::createValidator('required', $this, ['senha', 'senha2']);
            $requiredValidator->validateAttributes($this, ['senha', 'senha2']);
            
            $lengthValidator = Validator::createValidator('string', $this, 'senha', ['min' => 8]);
            $lengthValidator->validateAttribute($this, 'senha');
            
            $compareValidator = Validator::createValidator('compare', $this, 'senha', ['compareAttribute' => 'senha2']);
            $compareValidator->validateAttribute($this, 'senha');
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
     * @param ActiveQuery $query
     * @param Usuario $usuario
     */
    public static function doNivelDoUsuario($query, Usuario $usuario)
    {
        if ($usuario->role->id != UsuarioRole::ROOT) {
            
            $query->andWhere('usuario_role_id <> ' . UsuarioRole::ROOT);
            
            if ($usuario->municipio_id) {
                $query->andWhere(['municipio_id' => $usuario->municipio_id]);
            }
        }
    }

    /**
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
    }
    
    /**
     * @return UsuarioRole
     */
    public function getRole()
    {
        return $this->hasOne(UsuarioRole::className(), ['id' => 'usuario_role_id']);
    }

    public function beforeValidate()
    {
        if (!$this->sal) {
            $this->sal = uniqid();
        }

        return parent::beforeValidate();
    }

    public function afterValidate()
    {
        $return = parent::afterValidate();

        if ($this->errors) {
            return $return;
        }

        if ($this->usuario_role_id != UsuarioRole::ROOT && !$this->municipio_id) {
            $this->addError('municipio_id', 'Município não está definido');
        }

        return $return;
    }

    public function beforeSave($insert)
    {
        $return = parent::beforeSave($insert);
        
        if ($this->isNewRecord) {
            $this->senha = $this->senha ? $this->getEncryptedPassword() : null;
        } else {
            $this->senha = $this->senha ? $this->getEncryptedPassword() : $this->getOldAttribute('senha');
        }

        return $return;
    }

    /**
     * @return array descrição dos atributos (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'nome' => 'Nome',
            'login' => 'Login',
            'senha' => 'Senha',
            'senha2' => 'Repita a senha',
            'sal' => 'Sal',
            'municipio_id' => 'Município',
            'usuario_role_id' => 'Nível do Usuário',
            'ultimo_login' => 'Último Login',
            'email' => 'E-mail',
            'token_recupera_senha' => 'Token de Recuperação de Senha',
            'data_recupera_senha' => 'Data de Recuperação de Senha',
            'excluido' => 'Excluído',
        );
    }

    /**
     * Codificação de senha
     * 
     * @param string $sal
     * @param string $senha
     * @return string 
     */
    public static function encryptPassword($sal, $password)
    {
        return md5($password . $sal);
    }

    /**
     * Busca senha criptografada
     * @param string $senha Default get user password
     * @return string Senha criptografada considerando o sal do usuario
     */
    public function getEncryptedPassword($senha = null)
    {
        $senha = $senha ? $senha : $this->senha;

        return self::encryptPassword($this->sal, $senha);
    }

    /**
     * Autentica usuário
     * @param string $login
     * @param string $senha
     * @return boolean
     */
    public function validatePassword($senha)
    {
        $senhaCriptografada = $this->encryptPassword($this->sal, $senha);

        if ($senhaCriptografada !== $this->senha) {
            return false;
        }

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
            } else {
                // Se ele dá um find sem trazer a coluna sal, não deve recriar o sal - ele já existe!!!
                throw new \Exception('Você deve construir o objeto trazendo o SAL para poder alterar a SENHA');
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
     */
    public function delete()
    {
        $this->excluido = true;
        return (bool) $this->update(false, ['excluido']);
    }

    /**
     * @return string
     */
    public function getRBACRole()
    {
        switch ($this->usuario_role_id) {
            case UsuarioRole::ROOT:
                return 'Root';
                break;
            case UsuarioRole::ADMINISTRADOR:
                return 'Administrador';
                break;
            case UsuarioRole::GERENTE:
                return 'Gerente';
                break;
            case UsuarioRole::USUARIO:
                return 'Usuario';
                break;
            default:
                return null;
        }
    }
}
