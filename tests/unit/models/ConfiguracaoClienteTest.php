<?php

namespace tests\unit\models;

use app\models\Configuracao;
use app\models\ConfiguracaoCliente;
use app\models\ConfiguracaoTipo;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class ConfiguracaoClienteTest extends TestCase
{
    public function testValidateInteger()
    {
        $cliente = Phactory::cliente();

        $configuracao = Phactory::configuracao('inteiro');

        $configuracaoCliente = new ConfiguracaoCliente;
        $configuracaoCliente->cliente_id = $cliente->id;
        $configuracaoCliente->configuracao_id = $configuracao->id;
        $configuracaoCliente->valor = 'asd';

        $this->assertFalse($configuracaoCliente->validate());

        $configuracaoCliente->valor = '12';

        $this->assertTrue($configuracaoCliente->validate());
    }

    public function testValidateDecimal()
    {
        $cliente = Phactory::cliente();

        $configuracao = Phactory::configuracao('decimal');

        $configuracaoCliente = new ConfiguracaoCliente;
        $configuracaoCliente->cliente_id = $cliente->id;
        $configuracaoCliente->configuracao_id = $configuracao->id;
        $configuracaoCliente->valor = 'asd';

        $this->assertFalse($configuracaoCliente->validate());

        $configuracaoCliente->valor = '12.25';

        $this->assertTrue($configuracaoCliente->validate());
    }

    public function testValidateBooleano()
    {
        $cliente = Phactory::cliente();

        $configuracao = Phactory::configuracao('boleano');

        $configuracaoCliente = new ConfiguracaoCliente;
        $configuracaoCliente->cliente_id = $cliente->id;
        $configuracaoCliente->configuracao_id = $configuracao->id;

        $configuracaoCliente->valor = 'true';

        $this->assertTrue($configuracaoCliente->validate());
    }

    public function testValidateRange()
    {
        $cliente = Phactory::cliente();

        $configuracao = Phactory::configuracao('range');

        $configuracaoCliente = new ConfiguracaoCliente;
        $configuracaoCliente->cliente_id = $cliente->id;
        $configuracaoCliente->configuracao_id = $configuracao->id;
        $configuracaoCliente->valor = '3';

        $this->assertFalse($configuracaoCliente->validate());

        $configuracaoCliente->valor = '1';

        $this->assertTrue($configuracaoCliente->validate());
    }

    public function testValidateTime()
    {
        $cliente = Phactory::cliente();

        $configuracao = Phactory::configuracao('time');

        $configuracaoCliente = new ConfiguracaoCliente;
        $configuracaoCliente->cliente_id = $cliente->id;
        $configuracaoCliente->configuracao_id = $configuracao->id;
        $configuracaoCliente->valor = '25:00:02';

        $this->assertFalse($configuracaoCliente->validate());

        $configuracaoCliente->valor = '10:00:05';

        $this->assertTrue($configuracaoCliente->validate());
    }
}
