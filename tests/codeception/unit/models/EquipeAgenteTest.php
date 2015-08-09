<?php

namespace tests\unit\models;

use app\models\EquipeAgentes;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class EquipeAgenteTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new EquipeAgentes;
		$this->assertTrue($model != null);
	}
}
