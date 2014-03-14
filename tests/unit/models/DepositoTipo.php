<?php

namespace tests\unit\models;

use app\models\DepositoTipo;
use yii\codeception\TestCase;
use yii\db\Expression;

class DepositoTipoTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new DepositoTipo;
		$this->assertTrue($model != null);
	}
}