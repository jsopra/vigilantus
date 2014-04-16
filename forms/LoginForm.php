<?php

namespace app\forms;

use app\models\Usuario;
use Yii;
use yii\base\Model;
use app\models\Municipio;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{

    public $username;
    public $password;
    public $rememberMe = true;
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
            ['rememberMe', 'boolean'],
        ];
    }
    
    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Usuário',
            'password' => 'Senha',
            'rememberMe' => 'Continuar conectado'
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        $user = $this->getUser();

        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError('password', 'Usuário ou senha inválida.');
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            $logado = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            
            if ($logado) {
                $municipios = Municipio::getMunicipios($this->getUser()->municipio_id);
                $municipio = count($municipios) > 0 ? $municipios[0] : null;
                Yii::$app->session->set('user.municipio', $municipio);
            }
            
            return $logado;
            
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return Usuario|null
     */
    public function getUser()
    {
        if ($this->_user === false) {

            $this->_user = Usuario::find()
                ->ativo()
                ->where(['login' => $this->username])
                ->one()
            ;
        }
        
        return $this->_user;
    }
}
