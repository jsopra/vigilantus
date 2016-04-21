<?php

namespace app\models;

use app\components\ClienteActiveRecord;
use yii\web\IdentityInterface;
use Yii;

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
 * @property integer $cliente_id
 * @property integer $usuario_role_id
 * @property string $ultimo_login
 * @property string $email
 * @property string $token_recupera_senha
 * @property string $token_api
 * @property string $data_recupera_senha
 * @property boolean $excluido
 */
class Usuario extends ClienteActiveRecord implements IdentityInterface
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
        return static::find()->where(['token_api' => $token])->one();
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
        $temSenha = function ($model) {
            return ($model->isNewRecord || $model->senha != null);
        };

        $temSenhaJavaScript = 'function (attribute, value) {
            return jQuery("input[name=\'Usuario[senha]\']").val() != "";
        }';

        return [
            [
                ['senha', 'confirmacao_senha'],
                'required',
                'when' => $temSenha,
                'whenClient' => $temSenhaJavaScript,
            ],
            [
                ['senha', 'confirmacao_senha'],
                'string',
                'min' => 8,
                'when' => $temSenha,
                'whenClient' => $temSenhaJavaScript,
            ],
            [
                'senha',
                'compare',
                'compareAttribute' => 'confirmacao_senha',
                'when' => $temSenha,
                'whenClient' => $temSenhaJavaScript,
            ],
            [
                [
                    'nome', 'login', 'sal', 'senha_criptografada',
                    'usuario_role_id', 'email', 'cliente_id'
                ],
                'required'
            ],
            [['cliente_id', 'usuario_role_id'], 'integer'],
            ['login', 'unique'],
            ['email', 'unique'],
            ['token_api', 'unique', 'skipOnEmpty' => true],
            ['token_recupera_senha', 'unique', 'skipOnEmpty' => true],
            ['email', 'email'],
            [['data_recupera_senha'], 'date', 'time' => true],
            [['senha', 'confirmacao_senha', 'ultimo_login'], 'safe'],
            ['excluido', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
                'senha', 'confirmacao_senha', 'cliente_id', 'nome', 'login',
                '!sal', '!senha_criptografada', 'usuario_role_id', 'email',
                '!token_recupera_senha', '!ultimo_login', '!excluido',
                '!token_api', '!data_recupera_senha',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (!$this->sal) {
            $this->sal = uniqid();
        }

        if ($this->senha) {
            $this->senha_criptografada = self::encryptPassword(
                $this->sal,
                $this->senha
            );
        }

        if ($this->isNewRecord) {
            $this->token_api = uniqid();
        }

        return parent::beforeValidate();
    }

    /**
     * @return UsuarioRole
     */
    public function getRole()
    {
        return $this->hasOne(
            UsuarioRole::className(),
            ['id' => 'usuario_role_id']
        );
    }

    /**
     * @return array descrição dos atributos (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'login' => 'Login',
            'senha_criptografada' => 'Senha',
            'senha' => 'Senha',
            'confirmacao_senha' => 'Repita a senha',
            'sal' => 'Sal',
            'cliente_id' => 'Município Cliente',
            'usuario_role_id' => 'Nível do Usuário',
            'ultimo_login' => 'Último Login',
            'email' => 'E-mail',
            'token_recupera_senha' => 'Token de Recuperação de Senha',
            'data_recupera_senha' => 'Data de Recuperação de Senha',
            'excluido' => 'Excluído',
        ];
    }

    /**
     * Codificação de senha
     *
     * @param  string $sal
     * @param  string $senha
     * @return string
     */
    public static function encryptPassword($sal, $password)
    {
        return md5($password . $sal);
    }

    /**
     * Autentica usuário
     * @param  string  $login
     * @param  string  $senha
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
        if ($password != $confirmation) {
            return $this->addError('senha', 'A confirmação de senha não confere');
        }

        if (!$this->sal) {
            if (!$this->isNewRecord) {
                // Se ele busca sem a coluna sal, não deve recriar o sal - ele já existe!!!
                throw new \Exception(
                    'Você precisa do objeto com o SAL para poder alterar a SENHA'
                );
            }
            $this->sal = uniqid();
        }

        $this->senha_criptografada = Usuario::encryptPassword($this->sal, $password);
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
        $nomesRoles = [
            UsuarioRole::ROOT => 'Root',
            UsuarioRole::ADMINISTRADOR => 'Administrador',
            UsuarioRole::GERENTE => 'Gerente',
            UsuarioRole::USUARIO => 'Usuario',
            UsuarioRole::ANALISTA => 'Analista',
            UsuarioRole::TECNICO_LABORATORIAL => 'Tecnico Laboratorial',
        ];

        if (isset($nomesRoles[$this->usuario_role_id])) {
            return $nomesRoles[$this->usuario_role_id];
        }
    }

    /**
     * @return Cliente
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
    }

    /**
     * @param string $moduloId
     * @param  Cliente|null $cliente
     * @return boolean
     */
    public function moduloIsHabilitado($moduloId, $cliente = null)
    {
        if (!$cliente) {
            $cliente = $this->cliente;
        }

        return $cliente->moduloIsHabilitado($moduloId);
    }
}
