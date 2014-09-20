<?php

namespace app\models\report;

use app\models\Bairro;
use app\models\BoletimRg;
use app\models\BoletimRgFechamento;
use app\models\BoletimRgImovel;
use app\models\ImovelTipo;
use app\models\EspecieTransmissor;
use app\models\FocoTransmissor;
use app\models\DepositoTipo;

class ResumoFocosCapaReport
{
    public function getEspecieTransmissor()
    {
        return EspecieTransmissor::find()->orderBy('nome ASC')->all();
    }

    public function getTiposDepositos()
    {
        return DepositoTipo::find()->orderBy('descricao ASC')->all();
    }

    public function getFormasFoco()
    {
        return [
            'quantidade_forma_aquatica' => 'Aquática',
            'quantidade_forma_adulta' => 'Adulta',
            'quantidade_ovos' => 'Ovos',
        ];
    }

    public function getQuantidadeFocosTipoDeposito($ano, $especieId, $tipoDepositoId)
    {
        return FocoTransmissor::find()->doAno($ano)->daEspecieDeTransmissor($especieId)->doTipoDeposito($tipoDepositoId)->count();
    }

    public function getPercentualFocosTipoDeposito($ano, $especieId, $tipoDepositoId)
    {
        $qtdeDoTipo = FocoTransmissor::find()->doAno($ano)->daEspecieDeTransmissor($especieId)->doTipoDeposito($tipoDepositoId)->count();
        $qtdeTotal = FocoTransmissor::find()->doAno($ano)->daEspecieDeTransmissor($especieId)->count();

        return $qtdeTotal > 0 ? round((($qtdeDoTipo * 100) / $qtdeTotal),2) : 0;
    }

    public function getQuantidadeFocosFormaFoco($ano, $especieId, $formaFocoColumn)
    {
        return FocoTransmissor::find()->doAno($ano)->daEspecieDeTransmissor($especieId)->comQuantidadeEm($formaFocoColumn)->count();
    }

    public function getPercentualFocosFormaFoco($ano, $especieId, $formaFocoColumn)
    {
        $qtdeDoTipo = FocoTransmissor::find()->doAno($ano)->daEspecieDeTransmissor($especieId)->comQuantidadeEm($formaFocoColumn)->count();
        $qtdeTotal = FocoTransmissor::find()->doAno($ano)->daEspecieDeTransmissor($especieId)->count();

        return $qtdeTotal > 0 ? round((($qtdeDoTipo * 100) / $qtdeTotal),2) : 0;
    }

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
