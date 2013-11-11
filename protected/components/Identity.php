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

class Identity extends CUserIdentity
{
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
    * @var set quando a classe for instanciada por um serviço
    */
	private $_isAuthenticateWithService = false;

    /**
     * @var EAuthServiceBase the authorization service instance.
     */
    protected $authServiceBase;

    /**
     * Constructor.
     * @param EAuthServiceBase $authServiceBase the authorization service instance, default is FALSE
     */
    public function __construct($username, $password, $authServiceBase = false)
    {
    	if ($authServiceBase) {
        	$this->authServiceBase = $authServiceBase;
        	$this->_isAuthenticateWithService = true;
        	$this->username = $username;
        } else {
        	parent::__construct($username, $password);
        }

        $this->setState('username', $username);
    }

	/**
	 * Authenticates a user.
	 * Authenticates through a third-party service or through internal login LIVEALOOK.
	 *
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		// Authenticate with service
		if ($this->_isAuthenticateWithService) {

			if ($this->authServiceBase->isAuthenticated) {
	            $this->errorCode = self::ERROR_NONE;
				//@TODO verificar se vamos gravar algo na identidade do cara
	            //$this->username = $this->service->getAttribute('name');
	            //$this->setState('id', $this->service->id);
	            //$this->setState('service', $this->service->serviceName);
				
	        } else {
	            $this->errorCode = self::ERROR_NOT_AUTHENTICATED;
	        }

		} else {

			// Authenticate with internal login Livealook
			$user = Usuario::model()->findByAttributes(array('usuario' => $this->username));

			// no user found
			if ($user === null)
				$this->errorCode=self::ERROR_USERNAME_INVALID;
			// invalid password
			else if ($user->senha !== User::encryptPassword(Yii::app()->params['sal'], $this->password))
				$this->errorCode=self::ERROR_PASSWORD_INVALID;
			else
				$this->errorCode=self::ERROR_NONE;
		}

		// se autenticou, registra na sessão, a fim de utilizar a identidade em outras partes do sistema
		if (!$this->errorCode == true) {
			//@TODO verificar se existe algum problema em registrar na sessão a identidade do usuário
			Yii::app()->session['identity'] = $this;
			Yii::app()->session['user_object'] = $user;
		}

		return !$this->errorCode;
	}
}