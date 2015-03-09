<?php

namespace tests\unit\models;

use app\models\SocialAccount;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class SocialAccountTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new SocialAccount;
		$this->assertTrue($model != null);
	}
}