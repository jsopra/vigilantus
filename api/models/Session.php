<?php

namespace api\models;

use app\models\Usuario;
use Yii;
use yii\base\Model;

class Session extends Model
{
    public $login;
    public $password;
    public $auth_client_type;
    public $auth_client_id;
    public $access_token;

    private $_user = false;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['login', 'filter', 'filter' => 'strtolower'],
            ['password', 'validatePassword'],
            [['login', 'password'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'login' => 'Usuário',
            'password' => 'Senha',
        ];
    }

    /**
     * @return boolean
     */
    public function validatePassword()
    {
        $user = $this->getUser();

        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError('password', 'Usuário ou senha inválida.');
        }
    }

    /**
     * @return boolean
     */
    public function save()
    {
        if ($this->validate() && Yii::$app->user->login($this->getUser())) {
            $this->access_token = Yii::$app->user->identity->token_api;
            return true;
        }
        return false;
    }

    /**
     * @return array campos que aparecem na API ao serializar este objeto
     */
    public function fields()
    {
        return ['access_token'];
    }

    /**
     * Finds user by [[login]]
     *
     * @return Usuario|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Usuario::find()
                ->ativo()
                ->where(['login' => $this->login])
                ->one()
            ;
        }

        return $this->_user;
    }
}
