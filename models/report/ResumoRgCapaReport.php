<?php

namespace app\models\report;

use app\models\Bairro;
use app\models\ImovelTipo;
use app\models\BairroQuarteirao;
use app\models\redis\ResumoImovelFechamentoRg as ResumoImovelFechamentoRgRedis;
use app\models\redis\ResumoBairroFechamentoRg as ResumoBairroFechamentoRgRedis;

class ResumoRgCapaReport
{
    /**
     * @return integer
     */
    public function getTotalQuarteiroes()
    {
        return BairroQuarteirao::find()->count();
    }

    /**
     * @return integer
     */
    public function getTotalImoveis($idCliente)
    {
        return ResumoBairroFechamentoRgRedis::find()->doCliente($idCliente)->sum('quantidade');
    }

    /**
     * @return array
     */
    public function getImoveisPorTipo()
    {
        $dados = [];

        foreach (ImovelTipo::find()->ativo()->orderBy('nome')->all() as $tipoImovel) {

            $query = ResumoImovelFechamentoRgRedis::find()->doTipoImovel($tipoImovel->id)->one();

            if($query) {
                $dados[$tipoImovel->nome] = $query->quantidade;
            }

            if(!isset($dados[$tipoImovel->nome]) || !$dados[$tipoImovel->nome]) {
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

        foreach (Bairro::find()->orderBy('nome')->all() as $bairro) {

            $query = ResumoBairroFechamentoRgRedis::find()->doBairro($bairro->id)->one();

            if($query) {
                $dados[$bairro->nome] = $query->quantidade;
            }

            if(!isset($dados[$bairro->nome]) || !$dados[$bairro->nome]) {
                $dados[$bairro->nome] = 0;
            }
        }

        return $dados;
    }
}
