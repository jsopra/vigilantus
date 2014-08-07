<?php

namespace tests\unit\models;

use app\models\ClienteModulo;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class ClienteModuloTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new ClienteModulo;
		$this->assertTrue($model != null);
	}
}