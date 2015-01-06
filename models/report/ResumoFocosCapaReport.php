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
        return FocoTransmissor::find()->ativo()->daEspecieDeTransmissor($especieId)->doTipoDeposito($tipoDepositoId)->count();
    }

    public function getPercentualFocosTipoDeposito($ano, $especieId, $tipoDepositoId)
    {
        $qtdeDoTipo = FocoTransmissor::find()->ativo()->daEspecieDeTransmissor($especieId)->doTipoDeposito($tipoDepositoId)->count();
        $qtdeTotal = FocoTransmissor::find()->ativo()->daEspecieDeTransmissor($especieId)->count();

        return $qtdeTotal > 0 ? round((($qtdeDoTipo * 100) / $qtdeTotal),2) : 0;
    }

    public function getQuantidadeFocosFormaFoco($ano, $especieId, $formaFocoColumn)
    {
        return FocoTransmissor::find()->ativo()->daEspecieDeTransmissor($especieId)->comQuantidadeEm($formaFocoColumn)->count();
    }

    public function getPercentualFocosFormaFoco($ano, $especieId, $formaFocoColumn)
    {
        $qtdeDoTipo = FocoTransmissor::find()->ativo()->daEspecieDeTransmissor($especieId)->comQuantidadeEm($formaFocoColumn)->count();
        $qtdeTotal = FocoTransmissor::find()->ativo()->daEspecieDeTransmissor($especieId)->count();

        return $qtdeTotal > 0 ? round((($qtdeDoTipo * 100) / $qtdeTotal),2) : 0;
    }
}
