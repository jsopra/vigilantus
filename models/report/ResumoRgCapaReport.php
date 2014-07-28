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
        $query = BoletimRg::find();
        $query->andWhere('
            data = (
                SELECT MAX(data)
                FROM boletins_rg brg
                WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
            )
        ');
        
        return $query->count();
    }

    /**
     * @return integer
     */
    public function getTotalImoveis()
    {
        $totalImoveis = 0;
        
        $query = BoletimRgFechamento::find();
        $query->innerJoin('boletins_rg', 'boletim_rg_fechamento.boletim_rg_id=boletins_rg.id');
        $query->andWhere('
            boletins_rg.data = (
                SELECT MAX(data)
                FROM boletins_rg brg
                WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
            )
        ');
        
        $dados = $query->all();
        foreach ($dados as $imovelFechamento)
            $totalImoveis += $imovelFechamento->quantidade;
        
        return $totalImoveis;
    }

    /**
     * @return array
     */
    public function getImoveisPorTipo()
    {
        $dados = [];

        // Tipos de imóveis
        foreach (ImovelTipo::find()->ativo()->orderBy('nome')->all() as $tipoImovel)
            $dados[$tipoImovel->nome] = 0;

        // Valores dos tipos de imóveis
        $query = BoletimRgFechamento::find();
        $query->innerJoin('boletins_rg', 'boletim_rg_fechamento.boletim_rg_id=boletins_rg.id');
        $query->with(['imovelTipo' => function ($query) {
            $query->andWhere('excluido = FALSE');
        }]);
        $query->andWhere('
            boletins_rg.data = (
                SELECT MAX(data)
                FROM boletins_rg brg
                WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
            )
        ');
        
        $queryResults = $query->all();
        foreach ($queryResults as $boletim) {

            if (!$boletim->imovelTipo)
                continue;

            $dados[$boletim->imovelTipo->nome] += $boletim->quantidade;
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
        foreach (Bairro::find()->orderBy('nome')->all() as $bairro)
            $dados[$bairro->nome] = 0;

        // Bairros com informações
        $query = BoletimRgFechamento::find()->with('boletimRg.bairro');
        $query->innerJoin('boletins_rg', 'boletim_rg_fechamento.boletim_rg_id=boletins_rg.id');
        $query->andWhere('
            boletins_rg.data = (
                SELECT MAX(data)
                FROM boletins_rg brg
                WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
            )
        ');
        
        $queryResults = $query->all();
        foreach ($queryResults as $row)
            $dados[$row->boletimRg->bairro->nome] += $row->quantidade;
        
        return $dados;
    }
}
