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
        
        $query = BoletimRgFechamento::find()->doTipoLira(false);
        $query->innerJoin('boletins_rg', 'boletim_rg_fechamento.boletim_rg_id=boletins_rg.id');
        $query->andWhere('
            boletins_rg.data = (
                SELECT MAX(data)
                FROM boletins_rg brg
                WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
            )
        ');
        
        return $query->sum('quantidade');
    }

    /**
     * @return array
     */
    public function getImoveisPorTipo()
    {
        $dados = [];

        // Tipos de imóveis
        foreach (ImovelTipo::find()->ativo()->orderBy('nome')->all() as $tipoImovel) {

            // Valores dos tipos de imóveis
            $query = BoletimRgFechamento::find()->doTipoLira(false);
            $query->innerJoin('boletins_rg', 'boletim_rg_fechamento.boletim_rg_id=boletins_rg.id');

            $query->with(['imovelTipo' => function ($query) {
                $query->andWhere('excluido = FALSE');
            }]);

            $query->andWhere('imovel_tipo_id = ' . $tipoImovel->id);

            $query->andWhere('
                boletins_rg.data = (
                    SELECT MAX(data)
                    FROM boletins_rg brg
                    WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
                )
            ');
            
            $dados[$tipoImovel->nome] = $query->sum('quantidade');
            if(!$dados[$tipoImovel->nome]) {
                $dados[$tipoImovel->nome] = 0;
            }
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

            // Bairros com informações
            $query = BoletimRgFechamento::find()->doTipoLira(false)->with('boletimRg.bairro');
            $query->innerJoin('boletins_rg', 'boletim_rg_fechamento.boletim_rg_id=boletins_rg.id');
            $query->andWhere('
                boletins_rg.data = (
                    SELECT MAX(data)
                    FROM boletins_rg brg
                    WHERE brg.bairro_quarteirao_id = boletins_rg.bairro_quarteirao_id
                )
            ');

            $query->andWhere('boletins_rg.bairro_id = ' . $bairro->id);
            
            $dados[$bairro->nome] = $query->sum('quantidade');
            if(!$dados[$bairro->nome]) {
                $dados[$bairro->nome] = 0;
            }
        }
        
        return $dados;
    }
}
