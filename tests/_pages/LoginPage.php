<?php

namespace tests\_pages;

use yii\codeception\BasePage;

class LoginPage extends BasePage
{
	public $route = 'site/login';

	/**
	 * @param string $username
	 * @param string $password
	 */
	public function login($username, $password)
	{
		$this->guy->envioFormulario('#login-form', [
			'LoginForm[username]' => $username,
			'LoginForm[password]' => $password,
		]);
	}
}
