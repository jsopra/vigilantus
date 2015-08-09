<?php

namespace tests\unit\reports;

use app\models\report\ResumoRgBairroReport;
use Phactory;
use perspectiva\phactory\Test;

class ResumoRgBairroReportTest extends Test
{
    public function testGetData()
    {
        $boletim = Phactory::boletimRg(['data' => '07/03/1989']);
        $boletim->adicionarImovel('Rio de Janeiro', '176', null, 'Casa 1', 1, false);
        $boletim->adicionarImovel('Rio de Janeiro', '176', null, 'Casa 2', 1, false);
        $this->assertTrue($boletim->salvarComImoveis());

        $attributes = $boletim->attributes;
        $attributes['folha'] = '2';
        unset($attributes['id'], $attributes['bairro_quarteirao_id']);

        $boletim2 = Phactory::boletimRg($attributes);
        $boletim2->adicionarImovel('Rio de Janeiro', '176', null, 'Casa 1', 2, false);
        $boletim2->adicionarImovel('Rio de Janeiro', '176', null, 'Casa 2', 2, false);
        $boletim2->adicionarImovel('Rio de Janeiro', '176', null, 'Casa 3', 2, false);
        $this->assertTrue($boletim2->salvarComImoveis());

        $report = new ResumoRgBairroReport;
        $report->bairro_id = $boletim->bairro_id;

        $dadosRelatorio = $report->getData();
        $dadosEsperados = [
            $boletim->quarteirao->id => [
                'quarteirao' => $boletim->quarteirao->numero_quarteirao,
                'quarteirao_numero_alternativo' => $boletim->quarteirao->numero_quarteirao_2,
                'quarteirao_sequencia' => $boletim->quarteirao->seq,
                'imoveis' => [
                    1 => 2,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                ],
            ],
            $boletim2->quarteirao->id => [
                'quarteirao' => $boletim2->quarteirao->numero_quarteirao,
                'quarteirao_numero_alternativo' => $boletim2->quarteirao->numero_quarteirao_2,
                'quarteirao_sequencia' => $boletim2->quarteirao->seq,
                'imoveis' => [
                    1 => 0,
                    2 => 3,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                ],
            ]
        ];

        $this->assertEquals($dadosEsperados, $dadosRelatorio);
    }

    public function testTrazSomenteInformacaoMaisRecente()
    {
        $boletimAntigo = Phactory::boletimRg(['data' => '07/03/1989']);
        $boletimAntigo->adicionarImovel('Rio de Janeiro', '176', null, 'Casa 1', 1, false);
        $boletimAntigo->adicionarImovel('Rio de Janeiro', '176', null, 'Casa 2', 1, false);
        $this->assertTrue($boletimAntigo->salvarComImoveis());

        $attributes = $boletimAntigo->attributes;
        $attributes['data'] = '07/03/2014';
        $attributes['folha'] = '2';
        unset($attributes['id']);
        $boletimNovo = Phactory::boletimRg($attributes);
        $boletimNovo->adicionarImovel('Rio de Janeiro', '176', null, 'Casa 1', 1, false);
        $boletimNovo->adicionarImovel('Rio de Janeiro', '176', null, 'Casa 2', 1, false);
        $boletimNovo->adicionarImovel('Rio de Janeiro', '176', null, 'Casa 3', 1, false);
        $this->assertTrue($boletimNovo->salvarComImoveis());

        $report = new ResumoRgBairroReport;
        $report->bairro_id = $boletimAntigo->bairro_id;

        $dadosRelatorio = $report->getData();
        $dadosEsperados = [
            $boletimNovo->quarteirao->id => [
                'quarteirao' => $boletimNovo->quarteirao->numero_quarteirao,
                'quarteirao_numero_alternativo' => $boletimNovo->quarteirao->numero_quarteirao_2,
                'quarteirao_sequencia' => $boletimNovo->quarteirao->seq,
                'imoveis' => [
                    1 => 3,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                ],
            ]
        ];

        $this->assertEquals($dadosEsperados, $dadosRelatorio);
    }
}
