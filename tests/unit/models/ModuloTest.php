<?php

namespace tests\unit\models;

use app\models\Modulo;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class ModuloTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new Modulo;
		$this->assertTrue($model != null);
	}
}