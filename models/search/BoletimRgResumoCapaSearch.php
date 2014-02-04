<?php
namespace app\models\search;

use app\models\Bairro;
use app\models\BoletimRg;
use app\models\BoletimRgFechamento;
use app\models\BoletimRgImoveis;
use app\models\ImovelTipo;
use yii\base\Object;

class BoletimRgResumoCapaSearch extends Object
{
    /**
     * @return array
     */
    public static function porTiposDeImoveis()
    {
        $dados = [];

        // Quarteirões
        $dados['Quarteirões'] = [
            BoletimRg::find()->count(),
            BoletimRg::find()->comAreasDeFoco()->count(),
        ];

        // Tipos de imóveis
        foreach (ImovelTipo::find()->orderBy('nome')->all() as $tipoImovel) {
            $dados[$tipoImovel->nome] = [0, 0];
        }

        $dados['Total'] = [0, 0];

        // Valores dos tipos de imóveis
        foreach (BoletimRgImoveis::find()->with('imovelTipo')->all() as $boletim) {

            if ($boletim->area_de_foco) {
                $dados[$boletim->imovelTipo->nome][1]++;
                $dados['Total'][1]++;
            } else {
                $dados[$boletim->imovelTipo->nome][0]++;
                $dados['Total'][0]++;
            }
        }

        return $dados;
    }

    /**
     * @return array
     */
    public static function porBairros()
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