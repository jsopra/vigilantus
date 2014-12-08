<?php

namespace tests\unit\models;

use app\models\ConfiguracaoCliente;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class ConfiguracaoClienteTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new ConfiguracaoCliente;
		$this->assertTrue($model != null);
	}
}