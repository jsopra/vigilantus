<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel {

    public $username;
    public $password;
    public $rememberMe;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('username, password', 'required'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            // password needs to be validate
            array('password', 'validatePasswd'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'password' => Yii::t('site', 'Senha'),
            'username' => Yii::t('site', 'Usuário'),
            'rememberMe' => Yii::t('login', 'Lembrar-me da próxima vez'),
        );
    }

    /**
     * Authenticates the password.
     * This is the 'validatePasswd' validator as declared in rules().
     */
    public function validatePasswd() {
        if (!$this->hasErrors()) {
            $identity = new Identity($this->username, $this->password);
            if (!$identity->authenticate())
                $this->addError('password', Yii::t('login', 'Usuário ou senha incorretos.'));
        }
    }
}
