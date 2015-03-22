<?php

namespace tests\unit\models;

use app\models\Cliente;
use app\models\Configuracao;
use Phactory;
use fidelize\phactory\ActiveRecordTest;
use yii\db\Expression;

class ClienteTest extends ActiveRecordTest
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

		Phactory::clienteModulo(['cliente' => $clienteA]);
		Phactory::clienteModulo(['cliente' => $clienteA]);

		$this->assertEquals(2, $clienteA->quantidadeModulos);
		$this->assertEquals(0, $clienteB->quantidadeModulos);
	}

	public function testModuloIsHabilitado()
	{
		$clienteA = Phactory::cliente();
		$clienteB = Phactory::cliente();

		$modulo = Phactory::modulo();

		Phactory::clienteModulo(['cliente' => $clienteA, 'modulo' => $modulo]);

		$this->assertTrue($clienteA->moduloIsHabilitado($modulo->id));
		$this->assertFalse($clienteB->moduloIsHabilitado($modulo->id));
	}
}
