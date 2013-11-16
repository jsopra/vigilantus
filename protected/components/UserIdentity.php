<?php

/**
* Authenticates a user.
* LLIdentity represents the data needed to identity a user.
* It contains the authentication method that checks if the provided
* data can identity the user.
*
* @author Cristian de Oliveira <crisdeoliveira.si@gmail.com>
* @version 1.0
* @copyright 2012, livealook.com
* @license http://www.opensource.org/licenses/bsd-license.php
*/

class UserIdentity extends CUserIdentity
{
    /**
     * ID do usuário buscado através do login
     * @var integer 
     */
    protected $_id;
    
	/**
	* @var variable needed when a service is instantiated
	*
	* Others constants in class CBaseUserIdentity:
	* const ERROR_NONE = 0;
	* const ERROR_USERNAME_INVALID = 1;
	* const ERROR_PASSWORD_INVALID = 2;
	* const ERROR_UNKNOWN_IDENTITY = 100;
	*/
	const ERROR_NOT_AUTHENTICATED = 3;

	/**
	 * Authenticates a user.
	 * Authenticates through a third-party service or through internal login LIVEALOOK.
	 *
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() {
        // Authenticate with internal login Livealook
        $user = Usuario::model()->ativo()->findByAttributes(array('login' => $this->username));

        if($user===null) {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
			return !$this->errorCode;	
		}
        
        if(!$user->validatePassword($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id=$user->id;
            $this->username=$user->login;
            $this->errorCode=self::ERROR_NONE;
			
            $user->scenario = 'login';
			$user->ultimo_login = new CDbExpression('NOW()');
            $user->save();
			
		}
        
		return !$this->errorCode;
    }
        
    public function getId() {
        return $this->_id;
    }
}