<?php

namespace tests\unit\models;

use app\models\Configuracao;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class ConfiguracaoTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new Configuracao;
		$this->assertTrue($model != null);
	}
}