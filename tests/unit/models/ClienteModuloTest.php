<?php

namespace tests\unit\models;

use app\models\ClienteModulo;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class ClienteModuloTest extends TestCase
{
	public function testDoCliente()
	{
		$clienteA = Phactory::cliente();
		$clienteB = Phactory::cliente();

		$modulo = Phactory::modulo();

		Phactory::clienteModulo(['cliente_id' => $clienteA->id, 'modulo_id' => $modulo->id]);

		$this->assertEquals(1, ClienteModulo::find()->doCliente($clienteA->id)->count());

		$this->assertEquals(0, ClienteModulo::find()->doCliente($clienteB->id)->count());
	}
}