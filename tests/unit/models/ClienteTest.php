<?php

namespace tests\unit\models;

use app\models\Cliente;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class ClienteTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new Cliente;
		$this->assertTrue($model != null);
	}
}