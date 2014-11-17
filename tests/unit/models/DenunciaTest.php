<?php

namespace tests\unit\models;

use app\models\Denuncia;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class DenunciaTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new Denuncia;
		$this->assertTrue($model != null);
	}
}