<?php

namespace tests\unit\models;

use app\models\Configuracao;
use app\models\ConfiguracaoTipo;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class ConfiguracaoTest extends TestCase
{
    public function testValidateInteger()
    {
        $configuracao = new Configuracao;
        $configuracao->nome = 'teste de tipo';
        $configuracao->descricao = 'teste de tipo';
        $configuracao->tipo = ConfiguracaoTipo::TIPO_INTEIRO;
        $configuracao->valor = 'asd';

        $this->assertFalse($configuracao->validate());

        $configuracao->valor = '12';

        $this->assertTrue($configuracao->validate());
    }

    public function testValidateDecimal()
    {
        $configuracao = new Configuracao;
        $configuracao->nome = 'teste de tipo';
        $configuracao->descricao = 'teste de tipo';
        $configuracao->tipo = ConfiguracaoTipo::TIPO_DECIMAL;
        $configuracao->valor = 'asd';

        $this->assertFalse($configuracao->validate());

        $configuracao->valor = '12.25';

        $this->assertTrue($configuracao->validate());
    }

    public function testValidateBooleano()
    {
        $configuracao = new Configuracao;
        $configuracao->nome = 'teste de tipo';
        $configuracao->descricao = 'teste de tipo';
        $configuracao->tipo = ConfiguracaoTipo::TIPO_BOLEANO;
        $configuracao->valor = '0';
        $this->assertTrue($configuracao->validate());

        $configuracao->valor = '1';
        $this->assertTrue($configuracao->validate());
    }

    public function testValidateRange()
    {
        $configuracao = new Configuracao;
        $configuracao->nome = 'teste de tipo';
        $configuracao->descricao = 'teste de tipo';
        $configuracao->tipo = ConfiguracaoTipo::TIPO_RANGE;
        $configuracao->valor = '12';
        $configuracao->valores_possiveis = serialize(['1' => 'a', '2' => 'b']);

        $this->assertFalse($configuracao->validate());

        $configuracao->valor = '2';

        $this->assertTrue($configuracao->validate());
    }

    public function testValidateTime()
    {
        $configuracao = new Configuracao;
        $configuracao->nome = 'teste de tipo';
        $configuracao->descricao = 'teste de tipo';
        $configuracao->tipo = ConfiguracaoTipo::TIPO_TIME;
        $configuracao->valor = '25:00:02';

        $this->assertFalse($configuracao->validate());

        $configuracao->valor = '22:00:02';

        $this->assertTrue($configuracao->validate());
    }

    public function testGetValorString()
    {
        $configuracao = Phactory::configuracao(['valor' => 'string']);

        $this->assertEquals('string', $configuracao->getValor());
    }

    public function testGetValorInteger()
    {
        $configuracao = Phactory::configuracao('inteiro', ['valor' => '12']);

        $this->assertEquals(12, $configuracao->getValor());
    }

    public function testGetValorDecimal()
    {
        $configuracao = Phactory::configuracao('decimal', ['valor' => '12.15']);

        $this->assertEquals(12.15, $configuracao->getValor());
    }

    public function testGetValorBoleano()
    {
        $configuracao = Phactory::configuracao('boleano');

        $this->assertEquals(false, $configuracao->getValor());
    }

    public function testGetValorRange()
    {
        $configuracao = Phactory::configuracao('range', ['valor' => '2']);

        $this->assertEquals('b', $configuracao->getValor());
    }

    public function testGetValorTime()
    {
        $configuracao = Phactory::configuracao('time', ['valor' => '10:00:00']);

        $this->assertEquals('10:00:00', $configuracao->getValor());
    }

    public function testGetValorDescricaoString()
    {
        $configuracao = Phactory::configuracao(['valor' => 'string']);

        $this->assertEquals('string', $configuracao->getValor());
    }

    public function testGetValorDescricaoInteger()
    {
        $configuracao = Phactory::configuracao('inteiro', ['valor' => '12']);

        $this->assertEquals(12, $configuracao->getDescricaoValor());
    }

    public function testGetValorDescricaoDecimal()
    {
        $configuracao = Phactory::configuracao('decimal', ['valor' => '12.15']);

        $this->assertEquals(12.15, $configuracao->getDescricaoValor());
    }

    public function testGetValorDescricaoBoleano()
    {
        $configuracao = Phactory::configuracao('boleano');

        $this->assertEquals('Não', $configuracao->getDescricaoValor());
    }

    public function testGetValorDescricaoRange()
    {
        $configuracao = Phactory::configuracao('range', ['valor' => '2']);

        $this->assertEquals('b', $configuracao->getDescricaoValor());
    }

    public function testGetValorDescricaoTime()
    {
        $configuracao = Phactory::configuracao('time', ['valor' => '10:00:00']);

        $this->assertEquals('10:00:00', $configuracao->getDescricaoValor());
    }

    public function testCria()
    {
        $statusCriacao = Configuracao::cria(
            2,
            'teste criacao',
            'teste criacao',
            ConfiguracaoTipo::TIPO_STRING,
            'teste'
        );

        $this->assertTrue($statusCriacao);

        $statusCriacao = Configuracao::cria(
            3,
            'teste criacao',
            'teste criacao',
            ConfiguracaoTipo::TIPO_INTEIRO,
            'teste'
        );

        $this->assertFalse($statusCriacao);
    }

    public function testGetValorConfiguracaoCliente()
    {
        $cliente = Phactory::cliente();

        $configuracao = Phactory::configuracao(['valor' => 'default']);

        $configuracaoCliente = Phactory::configuracaoCliente(['configuracao_id' => $configuracao, 'cliente_id' => $cliente]);
        $configuracaoCliente->valor = 'customizado';

        $this->assertTrue($configuracaoCliente->save());

        $this->assertEquals('default', $configuracao->getValor());

        $this->assertEquals('customizado', $configuracao->getValor($cliente->id));

        $this->assertEquals('customizado', Configuracao::getValorConfiguracaoParaCliente($configuracao->id, $cliente->id));
    }
}
