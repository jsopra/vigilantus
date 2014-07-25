<?php

namespace tests\unit\models;

use app\models\Usuario;
use Phactory;
use Yii;
use tests\TestCase;

class LoginFormTest extends TestCase
{
	
	use \Codeception\Specify;

	public function testLoginNoUser()
	{
		$model = $this->mockUser(null);

		$model->username = 'some_username';
		$model->password = 'some_password';

		$this->specify('user should not be able to login, when there is no identity' , function () use ($model) {
			$this->assertFalse($model->login());
			$this->assertTrue(Yii::$app->user->isGuest,'user should not be logged in');
		});
	}

	public function testLoginWrongPassword()
	{
		$model = $this->mockUser(new Usuario);

		$model->username = 'administrador';
		$model->password = 'wrong-password';

		$this->specify('user should not be able to login with wrong password', function () use ($model) {
			$this->assertFalse($model->login());
			$this->assertArrayHasKey('password',$model->errors);
			$this->assertTrue(Yii::$app->user->isGuest,'user should not be logged in');
		});
	}

	public function testLoginCorrect()
	{
		$admin = Phactory::usuario('administrador');

		$model = $this->mockUser($admin);

		$model->username = $admin->login;
		$model->password = '12345678';

		$this->specify('user should be able to login with correct credentials', function() use ($model) {
			$this->assertTrue($model->login());
			$this->assertArrayNotHasKey('password',$model->errors);
			$this->assertFalse(Yii::$app->user->isGuest,'user should be logged in');
		});
	}

	private function mockUser($user)
	{
		$loginForm = $this->getMock('app\forms\LoginForm',['getUser']);
		$loginForm->expects($this->any())->method('getUser')->will($this->returnValue($user));
		return $loginForm;
	}

}