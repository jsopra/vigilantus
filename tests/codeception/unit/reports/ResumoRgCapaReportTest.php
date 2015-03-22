<?php

namespace tests\unit\reports;

use app\models\ImovelTipo;
use app\models\report\ResumoRgCapaReport;
use Phactory;
use fidelize\phactory\Test;

class ResumoRgCapaReportTest extends Test
{
    protected $report;
    protected $_cliente;

    public function setUp()
    {
        parent::setUp();

        $this->_cliente = Phactory::cliente();

        ImovelTipo::deleteAll();

        $casa = Phactory::imovelTipo(['nome' => 'Casa', 'cliente_id' => $this->_cliente->id]);
        $terreno = Phactory::imovelTipo(['nome' => 'Terreno', 'cliente_id' => $this->_cliente->id]);

        $baseDados = [
            'Seminário' => [
                '120' => [$casa->id => 0, $terreno->id => 4], // = 4
                '121' => [$casa->id => 10, $terreno->id => 0], // = 10
                '122' => [$casa->id => 4, $terreno->id => 1], // = 5
                // = 19 imoveis, 14 casa + 5 terreno
            ],
            'Palmital' => [
                '130' => [$casa->id => 2, $terreno->id => 2], // = 4
                '131' => [$casa->id => 3, $terreno->id => 4], // = 5
                // 11 imoveis, 5 casa + 6 terreno
            ],
            // 2 bairros, 5 quarteiroes, 30 imóveis = 19 casa + 11 terreno
        ];

        $this->criarDados($baseDados, $this->_cliente);

        $this->report = new ResumoRgCapaReport;

        \app\jobs\RefreshFechamentoRgJob::run();
    }

    public function testGetTotalQuarteiroes()
    {
        $this->assertEquals(5, $this->report->getTotalQuarteiroes());
    }

    public function testGetTotalImoveis()
    {
        $this->assertEquals(30, $this->report->getTotalImoveis($this->_cliente->id));
    }

    public function testGetImoveisPorTipo()
    {
        $this->assertEquals(
            [
                'Casa' => 19,
                'Terreno' => 11,
            ],
            $this->report->getImoveisPorTipo()
        );
    }

    public function testGetImoveisPorBairro()
    {
        $this->assertEquals(
            [
                'Palmital' => 11,
                'Seminário' => 19,
            ],
            $this->report->getImoveisPorBairro()
        );
    }

    protected function criarDados($baseDados, $cliente)
    {
        foreach ($baseDados as $nomeBairro => $quarteiroes) {

            $bairro = Phactory::bairro([
                'nome' => $nomeBairro,
                'cliente_id' => $cliente->id,
            ]);

            foreach ($quarteiroes as $numero => $imoveisPorTipo) {

                $quarteirao = Phactory::bairroQuarteirao([
                    'numero_quarteirao' => (string) $numero,
                    'cliente_id' => $cliente->id,
                    'bairro_id' => $bairro->id,
                ]);

                $boletim = Phactory::boletimRg([
                    'data' => '07/03/1989',
                    'cliente_id' => $cliente->id,
                    'bairro_id' => $bairro->id,
                    'bairro_quarteirao_id' => $quarteirao->id,
                ]);

                foreach ($imoveisPorTipo as $tipoImovel => $quantidade) {
                    for ($n = 1; $n <= $quantidade; $n++) {
                        $boletim->adicionarImovel('Avenida', strval($n), null, null, $tipoImovel, false);
                    }
                }

                $this->assertTrue($boletim->salvarComImoveis());
            }
        }
    }
}
