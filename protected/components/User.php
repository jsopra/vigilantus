<?php

/**
* System access.
* LLUser contains the methods used by the user to access the system.
*
* @author Cristian de Oliveira <crisdeoliveira.si@gmail.com>
* @version 1.0
* @copyright 2012, livealook.com
* @license http://www.opensource.org/licenses/bsd-license.php
*/

class User
{

	private $_identity;

	/**
	* Desloga da aplicação
	* 
	* @return void
	*/
    public static function logout() 
    {
        
        Yii::app()->user->logout();
    }

	/**
	* Login interno - Livealook
	* @return boolean whether login is successful
	*/
    public function internalLogin($username, $password, $rememberMe = false) 
    {
        if ($this->_identity === null) {
            $this->_identity = new Identity($username, $password);
            $this->_identity->authenticate();
        }

        if ($this->_identity->errorCode === Identity::ERROR_NONE) {
            $duration = $rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        }
        else
            return false;
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
    
}