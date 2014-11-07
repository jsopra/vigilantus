<?php

namespace tests\unit\models;

use Phactory;
use app\models\BoletimRg;
use app\models\BoletimRgFechamento;
use app\models\Imovel;
use app\models\Rua;
use tests\TestCase;

class BoletimRgTest extends TestCase
{
    public $primeiroBoletimID;

    public function testAdicionarImovel()
    {
        $boletimRg = Phactory::boletimRg();

        $boletimRg->adicionarImovel('Rio de Janeiro', '176', null, 'AP 703', 1, false);

        $arrayEsperado = [
            [
                'rua' => 'Rio de Janeiro',
                'numero' => '176',
                'seq' => null,
                'complemento' => 'AP 703',
                'imovel_tipo' => 1,
                'imovel_lira' => false,
            ]
        ];

        $this->assertEquals($arrayEsperado, $boletimRg->imoveis);
    }

    public function testCRUD()
    {
        /*
         * boletim 1
         */
        $bairro = Phactory::bairro();
        $quarteirao = Phactory::bairroQuarteirao();

        $boletim = new BoletimRg;

        $this->assertFalse($boletim->validate());

        $this->assertArrayHasKey('bairro_id', $boletim->errors);
        $this->assertArrayHasKey('municipio_id', $boletim->errors);
        $this->assertArrayHasKey('bairro_quarteirao_id', $boletim->errors);
        $this->assertArrayHasKey('data', $boletim->errors);

        $boletim->municipio_id = 1;
        $boletim->inserido_por = 1;
        $boletim->bairro_id = $bairro->id;
        $boletim->bairro_quarteirao_id = $quarteirao->id;
        $boletim->data = date('d/m/Y');
        $boletim->inserido_por = 1;
        $boletim->categoria_id = $bairro->bairro_categoria_id;
        $boletim->imoveis = array(
            $this->criarArrayImovel('Rio de Janeiro', '176', null, 'AP 705', false, 1),
            $this->criarArrayImovel('Rio de Janeiro', '176', null, 'AP 704', false, 1),
            $this->criarArrayImovel('Rio de Janeiro', '176', null, 'AP 703', false, 1),
            $this->criarArrayImovel('Rio de Janeiro', '173', null, null, true, 2),
        );

        $this->assertTrue($boletim->salvarComImoveis());
        $this->assertEquals(4, $boletim->quantidadeImoveis);

        $this->verificarFechamentos([
            // Boletim, tipo imóvel, LIRA?, registros no banco, quantidade
            [$boletim->id, 1, false, 1, 3],
            [$boletim->id, 1, true, 0, null],
            [$boletim->id, 2, false, 1, 1],
            [$boletim->id, 2, true, 1, 1],
        ]);

        $rua = Rua::find()->daRua('Rio de Janeiro')->one();
        $this->assertInstanceOf(Rua::className(), $rua);

        $this->verificarImoveis([
            // $rua, $numero, $complemento, $tipoLira
            [$rua->id, 176, 'AP 705', false],
            [$rua->id, 176, 'AP 704', false],
            [$rua->id, 176, 'AP 703', false],
            [$rua->id, 173, null, true],
        ]);

        $this->primeiroBoletimID = $boletim->id;

        /*
         * boletim 2
         */
        $bairro = Phactory::bairro();
        $quarteirao = Phactory::bairroQuarteirao();

        $boletim = new BoletimRg;

        $this->assertFalse($boletim->validate());

        $this->assertArrayHasKey('bairro_id', $boletim->errors);
        $this->assertArrayHasKey('municipio_id', $boletim->errors);
        $this->assertArrayHasKey('bairro_quarteirao_id', $boletim->errors);
        $this->assertArrayHasKey('data', $boletim->errors);

        $boletim->municipio_id = 1;
        $boletim->inserido_por = 1;
        $boletim->bairro_id = $bairro->id;
        $boletim->bairro_quarteirao_id = $quarteirao->id;
        $boletim->data = date('d/m/Y');
        $boletim->inserido_por = 1;
        $boletim->categoria_id = $bairro->bairro_categoria_id;

        $boletim->imoveis = array(
            $this->criarArrayImovel('Vitorio Cella', '176', null, 'AP 401', false, 1),
            $this->criarArrayImovel('Vitorio Cella', '176', null, 'AP 402', false, 1),
            $this->criarArrayImovel('Vitorio Cella', '176', null, 'AP 403', false, 1),
            $this->criarArrayImovel('Vitorio Cella', '176', null, 'AP 404', false, 1),
            $this->criarArrayImovel('Vitorio Cella', '176', null, 'AP 405', false, 1),
            $this->criarArrayImovel('Vitorio Cella', '178', null, null, true, 3),
            $this->criarArrayImovel('Vitorio Cella', '180', null, null, true, 2),
            $this->criarArrayImovel('Vitorio Cella', '182', null, null, true, 2),
        );

        $this->assertTrue($boletim->salvarComImoveis());
        $this->assertEquals(8, $boletim->quantidadeImoveis);

        $this->verificarFechamentos([
            // Boletim, tipo imóvel, LIRA?, registros no banco, quantidade
            [$boletim->id, 1, false, 1, 5],
            [$boletim->id, 1, true, 0, null],
            [$boletim->id, 2, false, 1, 2],
            [$boletim->id, 2, true, 1, 2],
            [$boletim->id, 3, false, 1, 1],
            [$boletim->id, 3, true, 1, 1],
        ]);

        $rua = Rua::find()->daRua('Vitorio Cella')->one();

        $this->verificarImoveis([
            // $rua, $numero, $complemento, $tipoLira
            [$rua->id, 176, 'AP 401', false],
            [$rua->id, 176, 'AP 402', false],
            [$rua->id, 176, 'AP 403', false],
            [$rua->id, 176, 'AP 404', false],
            [$rua->id, 176, 'AP 405', false],
            [$rua->id, 178, null, true],
            [$rua->id, 180, null, true],
            [$rua->id, 182, null, true],
        ]);

        /*
         * Update model 2
         */
        $boletimUpdate = BoletimRg::findOne($boletim->id);
        $this->assertInstanceOf('app\models\BoletimRg', $boletimUpdate);

        $rua = Rua::find()->daRua('Vitorio Cella')->one();
        $this->assertInstanceOf('app\models\Rua', $rua);

        $this->verificarImoveis([
            // $rua, $numero, $complemento, $tipoLira
            [$rua->id, 176, 'AP 401', false],
        ]);

        $boletimUpdate->imoveis = array(
            $this->criarArrayImovel('Vitorio Cella', '176', null, 'AP 401', true, 1),
            $this->criarArrayImovel('Vitorio Cella', '178', null, null, true, 3),
            $this->criarArrayImovel('Vitorio Cella', '180', null, null, true, 2),
            $this->criarArrayImovel('Vitorio Cella', '182', null, null, true, 2),
        );

        $this->assertTrue($boletimUpdate->salvarComImoveis());

        $this->assertEquals(4, $boletimUpdate->quantidadeImoveis);

        $this->verificarFechamentos([
            // Boletim, tipo imóvel, LIRA?, registros no banco, quantidade
            [$boletimUpdate->id, 1, false, 1, 1],
            [$boletimUpdate->id, 1, true, 1, 1],
            [$boletimUpdate->id, 2, false, 1, 2],
            [$boletimUpdate->id, 2, true, 1, 2],
            [$boletimUpdate->id, 3, false, 1, 1],
            [$boletimUpdate->id, 3, true, 1, 1],
        ]);

        /*
         * Test delete
         */
        //deleta o boletim
        $boletimDelete = BoletimRg::findOne($boletim->id);
        $this->assertInstanceOf('app\models\BoletimRg', $boletimDelete);

        $this->assertEquals(1, $boletimDelete->delete());
        $this->assertEquals(0, BoletimRgFechamento::find()->doBoletim($boletim->id)->count());

        //mantem todas ruas e imoveis
        $rua = Rua::find()->daRua('Vitorio Cella')->one();
        $this->assertInstanceOf(Rua::className(), $rua);

        $this->verificarImoveis([
            // $rua, $numero, $complemento, $tipoLira
            [$rua->id, 176, 'AP 401', true],
            [$rua->id, 176, 'AP 402', false],
            [$rua->id, 176, 'AP 403', false],
            [$rua->id, 176, 'AP 404', false],
            [$rua->id, 176, 'AP 405', false],
            [$rua->id, 178, null, true],
            [$rua->id, 180, null, true],
            [$rua->id, 182, null, true],
        ]);

        //mantem tudo do primeiro boletim
        unset($boletim);
        $boletimPrimeiro = BoletimRg::findOne($this->primeiroBoletimID);
        $this->assertInstanceOf('app\models\BoletimRg', $boletimPrimeiro);

        $this->assertEquals(4, $boletimPrimeiro->quantidadeImoveis);

        $this->verificarFechamentos([
            // Boletim, tipo imóvel, LIRA?, registros no banco, quantidade
            [$boletimPrimeiro->id, 1, false, 1, 3],
            [$boletimPrimeiro->id, 1, true, 0, null],
            [$boletimPrimeiro->id, 2, false, 1, 1],
            [$boletimPrimeiro->id, 2, true, 1, 1],
        ]);

        $rua = Rua::find()->daRua('Rio de Janeiro')->one();
        $this->assertInstanceOf('app\models\Rua', $rua);

        $this->verificarImoveis([
            // $rua, $numero, $complemento, $tipoLira
            [$rua->id, 176, 'AP 705', false],
            [$rua->id, 176, 'AP 704', false],
            [$rua->id, 176, 'AP 703', false],
            [$rua->id, 173, null, true],
        ]);
    }

    /**
     * Cria um array que simula os dados enviados via $_POST no formulário
     * @param string $rua
     * @param string $numero
     * @param string $sequencia
     * @param string $complemento
     * @param boolean $lira
     * @param integer $tipo
     * @return array
     */
    protected function criarArrayImovel($rua, $numero, $sequencia, $complemento, $lira, $tipo)
    {
        return [
            'rua' => $rua,
            'numero' => $numero,
            'seq' => $sequencia,
            'complemento' => $complemento,
            'imovel_lira' => $lira,
            'imovel_tipo' => $tipo,
        ];
    }

    /**
     *
     * @param integer $boletim
     * @param integer $tipoImovel
     * @param boolean $tipoLira
     * @return ActiveQuery
     */
    protected function getBoletimRgFechamentoQuery($boletim, $tipoImovel, $tipoLira)
    {
        return BoletimRgFechamento::find()
                ->doBoletim($boletim)
                ->doTipoDeImovel($tipoImovel)
                ->doTipoLira($tipoLira)
        ;
    }

    /**
     * @param array $fechamentos
     */
    protected function verificarFechamentos(array $fechamentos)
    {
        foreach ($fechamentos as $data) {

            list($boletim, $tipoImovel, $tipoLira, $registros, $quantidade) = $data;

            $query = $this->getBoletimRgFechamentoQuery($boletim, $tipoImovel, $tipoLira);

            $this->assertEquals($registros, $query->count());

            if ($registros) {
                $this->assertEquals($quantidade, $query->one()->quantidade);
            }
        }
    }

    /**
     * @param array $imoveis
     */
    protected function verificarImoveis(array $imoveis)
    {
        foreach ($imoveis as $data) {

            list($rua, $numero, $complemento, $tipoLira) = $data;

            $imovel = $this->getImovel($rua, $numero, $complemento, $tipoLira);

            $this->assertInstanceOf(Imovel::className(), $imovel);
        }
    }

    /**
     * @param string $rua
     * @param integer $numero
     * @param string $complemento
     * @param boolean $tipoLira
     * @return Imovel
     */
    protected function getImovel($rua, $numero, $complemento, $tipoLira)
    {
        $query = Imovel::find()
            ->daRua($rua)
            ->doNumero($numero)
            ->doTipoLira($tipoLira)
        ;

        if ($complemento) {
            $query->doComplemento($complemento);
        }

        return $query->one();
    }
}
