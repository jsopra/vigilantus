<?php

namespace tests\unit\models;

use app\models\DenunciaTipoProblema;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class DenunciaTipoProblemaTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new DenunciaTipoProblema;
		$this->assertTrue($model != null);
	}
}