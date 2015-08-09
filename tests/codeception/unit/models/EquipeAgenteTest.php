<?php

namespace tests\unit\models;

use app\models\EquipeAgente;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class EquipeAgenteTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new EquipeAgente;
		$this->assertTrue($model != null);
	}
}
