<?php

namespace app\models\report;

use app\models\Bairro;
use app\models\BoletimRg;
use app\models\BoletimRgFechamento;
use app\models\BoletimRgImovel;
use app\models\ImovelTipo;

class ResumoRgCapaReport
{
    /**
     * @return integer
     */
    public function getTotalQuarteiroes()
    {
        return BoletimRg::find()->count();
    }

    /**
     * @return integer
     */
    public function getTotalImoveis()
    {
        return BoletimRgImovel::find()->count();
    }

    /**
     * @return array
     */
    public function getImoveisPorTipo()
    {
        $dados = [];

        // Tipos de imóveis
        foreach (ImovelTipo::find()->ativo()->orderBy('nome')->all() as $tipoImovel) {
            $dados[$tipoImovel->nome] = 0;
        }

        // Valores dos tipos de imóveis
        $query = BoletimRgImovel::find()->with(['imovelTipo' => function ($query) {
            $query->andWhere('excluido = FALSE');
        }]);

        foreach ($query->all() as $boletim) {

            if (!$boletim->imovelTipo) {
                continue;
            }

            $dados[$boletim->imovelTipo->nome]++;
        }

        return $dados;
    }

    /**
     * @return array
     */
    public function getImoveisPorBairro()
    {
        $dados = [];

        // Adiciona todos os bairros vazios
        foreach (Bairro::find()->orderBy('nome')->all() as $bairro) {
            $dados[$bairro->nome] = 0;
        }

        // Bairros com informações
        $query = BoletimRgFechamento::find()->with('boletimRg.bairro');
        foreach ($query->all() as $row) {
            $dados[$row->boletimRg->bairro->nome] += $row->quantidade;
        }

        return $dados;
    }
}
