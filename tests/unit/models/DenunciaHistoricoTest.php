<?php

namespace tests\unit\models;

use app\models\DenunciaHistorico;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class DenunciaHistoricoTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new DenunciaHistorico;
		$this->assertTrue($model != null);
	}
}