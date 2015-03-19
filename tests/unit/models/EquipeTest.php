<?php

namespace tests\unit\models;

use app\models\Equipe;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class EquipeTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new Equipe;
		$this->assertTrue($model != null);
	}
}