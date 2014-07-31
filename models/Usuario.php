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
 * @property string $confirmacao_senha
 * @property string $senha_criptografada
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
    /**
     * Atributos temporários utilizados para definir a senha_criptografada
     */
    public $senha;
    public $confirmacao_senha;

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
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // @TODO Obter usuário pelo token para uso em APIs
        return false;
    }
    
    /* Métodos ActiveRecord */

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
        $temSenha = function($model) {
            return ($model->isNewRecord || $model->senha != null);
        };

        $temSenhaJavaScript = 'function (attribute, value) {
            return jQuery("input[name=\'Usuario[senha]\']").val() != "";
        }';
        
        return array(
            [['senha', 'confirmacao_senha'], 'required',                    'when' => $temSenha, 'whenClient' => $temSenhaJavaScript],
            [['senha', 'confirmacao_senha'], 'string', 'min' => 8,          'when' => $temSenha, 'whenClient' => $temSenhaJavaScript],
            ['senha', 'compare', 'compareAttribute' => 'confirmacao_senha', 'when' => $temSenha, 'whenClient' => $temSenhaJavaScript],

            ['municipio_id', 'required', 'when' => function($model) {
                return $model->usuario_role_id != UsuarioRole::ROOT;
            }],

            [['nome', 'login', '!sal', '!senha_criptografada', 'usuario_role_id', 'email'], 'required'],
            [['municipio_id', 'usuario_role_id'], 'integer'],
            ['login', 'unique'],
            ['email', 'unique'],
            ['email', 'email'],
            [['senha', 'confirmacao_senha'], 'safe'],
        );
    }

    public function beforeValidate()
    {
        if (!$this->sal) {
            $this->sal = uniqid();
        }

        if ($this->senha) {
            $this->senha_criptografada = self::encryptPassword($this->sal, $this->senha);
        }

        return parent::beforeValidate();
    }

    /**
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->hasOne(Municipio::className(), ['id' => 'municipio_id']);
    }
    
    /**
     * @return Municipio
     */
    public function getMunicipioLogado() 
    {
        return Municipio::find()->one();//\Yii::$app->session->get('user.municipio');
    }
    
    /**
     * @return UsuarioRole
     */
    public function getRole()
    {
        return $this->hasOne(UsuarioRole::className(), ['id' => 'usuario_role_id']);
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
            'senha_criptografada' => 'Senha',
            'senha' => 'Senha',
            'confirmacao_senha' => 'Repita a senha',
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
     * Autentica usuário
     * @param string $login
     * @param string $senha
     * @return boolean
     */
    public function validatePassword($senha)
    {
        $senhaCriptografada = $this->encryptPassword($this->sal, $senha);

        return ($senhaCriptografada == $this->senha_criptografada);
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

        $this->senha_criptografada = $password;
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
