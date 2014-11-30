<?php

namespace tests\unit\models;

use app\models\Cliente;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class ClienteTest extends TestCase
{
	public function testDoRotulo()
	{
		$clienteA = Phactory::cliente(['rotulo' => 'chapeco']);
		$clienteB = Phactory::cliente(['rotulo' => 'xanxere']);

		$this->assertEquals(1, Cliente::find()->doRotulo('chapeco')->count());

		$this->assertEquals(0, Cliente::find()->doRotulo('xaxim')->count());

		$this->assertEquals(1, Cliente::find()->doRotulo('xanxere')->count());
	}

	public function testGetQuantidadeModulos()
	{
		$clienteA = Phactory::cliente();
		$clienteB = Phactory::cliente();

		$moduloA = Phactory::modulo();
		$moduloB = Phactory::modulo();

		Phactory::clienteModulo(['cliente_id' => $clienteA->id, 'modulo_id' => $moduloA->id]);
		Phactory::clienteModulo(['cliente_id' => $clienteA->id, 'modulo_id' => $moduloB->id]);

		$this->assertEquals(2, $clienteA->quantidadeModulos);

		$this->assertEquals(0, $clienteB->quantidadeModulos);
	}

	public function testModuloIsHabilitado()
	{
		$clienteA = Phactory::cliente();
		$clienteB = Phactory::cliente();

		$modulo = Phactory::modulo();

		Phactory::clienteModulo(['cliente_id' => $clienteA->id, 'modulo_id' => $modulo->id]);

		$this->assertTrue($clienteA->moduloIsHabilitado($modulo->id));

		$this->assertFalse($clienteB->moduloIsHabilitado($modulo->id));
	}
}