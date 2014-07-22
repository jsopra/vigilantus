<?php

namespace tests\unit\reports;

use app\models\ImovelTipo;
use app\models\report\ResumoRgCapaReport;
use Phactory;
use tests\TestCase;

class ResumoRgCapaReportTest extends TestCase
{
    protected $report;

    public function setUp()
    {
        parent::setUp();

        ImovelTipo::deleteAll();

        $municipio = Phactory::municipio();
        $casa = Phactory::imovelTipo(['nome' => 'Casa', 'municipio_id' => $municipio]);
        $terreno = Phactory::imovelTipo(['nome' => 'Terreno', 'municipio_id' => $municipio]);

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

        $this->criarDados($baseDados, $municipio);

        $this->report = new ResumoRgCapaReport;
    }

    public function testGetTotalQuarteiroes()
    {
        $this->assertEquals(5, $this->report->getTotalQuarteiroes());
    }

    public function testGetTotalImoveis()
    {
        $this->assertEquals(30, $this->report->getTotalImoveis());
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

    protected function criarDados($baseDados, $municipio)
    {
        foreach ($baseDados as $nomeBairro => $quarteiroes) {

            $bairro = Phactory::bairro([
                'nome' => $nomeBairro,
                'municipio_id' => $municipio->id,
            ]);

            foreach ($quarteiroes as $numero => $imoveisPorTipo) {

                $quarteirao = Phactory::bairroQuarteirao([
                    'numero_quarteirao' => (string) $numero,
                    'municipio_id' => $municipio->id,
                    'bairro_id' => $bairro->id,
                ]);

                $boletim = Phactory::boletimRg([
                    'data' => '07/03/1989',
                    'municipio_id' => $municipio->id,
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
