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
        $dados = [
            'quarteiroes' => [
                'geral' => BoletimRg::find()->count(),
                'areasDeFoco' => BoletimRg::find()->comAreasDeFoco()->count(),
            ],
            'tipos_imoveis' => [],
            'total' => [
                'geral' => 0,
                'areasDeFoco' => 0
            ]
        ];

        // Tipos de imóveis
        foreach (ImovelTipo::find()->ativo()->orderBy('nome')->all() as $tipoImovel) {
            $dados['tipos_imoveis'][$tipoImovel->nome] = [
                'geral' => 0,
                'areasDeFoco' => 0
            ];
        }

        // Valores dos tipos de imóveis
        $query = BoletimRgImoveis::find()->with(['imovelTipo' => function($query) {
            $query->andWhere('excluido = FALSE');
        }]);
        foreach ($query->all() as $boletim) {

            if ($boletim->area_de_foco) {
                $dados['tipos_imoveis'][$boletim->imovelTipo->nome]['areasDeFoco']++;
                $dados['total']['areasDeFoco']++;
            }
            $dados['tipos_imoveis'][$boletim->imovelTipo->nome]['geral']++;
            $dados['total']['geral']++;
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