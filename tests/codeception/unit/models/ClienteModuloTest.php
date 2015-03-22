<?php

namespace tests\unit\models;

use app\models\ClienteModulo;
use Phactory;
use fidelize\phactory\ActiveRecordTest;
use yii\db\Expression;

class ClienteModuloTest extends ActiveRecordTest
{
	public function testDoCliente()
	{
		$clienteA = Phactory::cliente();
		$clienteB = Phactory::cliente();

		$modulo = Phactory::modulo();

		Phactory::clienteModulo(['cliente' => $clienteA, 'modulo' => $modulo]);

		$this->assertEquals(1, ClienteModulo::find()->doCliente($clienteA->id)->count());
		$this->assertEquals(0, ClienteModulo::find()->doCliente($clienteB->id)->count());
	}
}