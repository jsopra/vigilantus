<?php

namespace app\models\report;

use app\models\Bairro;
use app\models\BoletimRg;
use app\models\BoletimRgFechamento;
use app\models\BoletimRgImovel;
use app\models\ImovelTipo;
use app\models\BairroQuarteirao;
use app\models\redis\FechamentoRg as FechamentoRgRedis;

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
        return FechamentoRgRedis::find()->doTipoLira(false)->doCliente($idCliente)->sum('quantidade');
    }

    /**
     * @return array
     */
    public function getImoveisPorTipo()
    {
        $dados = [];

        foreach (ImovelTipo::find()->ativo()->orderBy('nome')->all() as $tipoImovel) {

            $query = FechamentoRgRedis::find()->doTipoLira(false)->doTipoImovel($tipoImovel->id);

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

        foreach (Bairro::find()->orderBy('nome')->all() as $bairro) {

            $query = FechamentoRgRedis::find()->doTipoLira(false)->doBairro($bairro->id);

            $dados[$bairro->nome] = $query->sum('quantidade');
            if(!$dados[$bairro->nome]) {
                $dados[$bairro->nome] = 0;
            }
        }
        
        return $dados;
    }
}
