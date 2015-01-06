<?php

namespace app\models\search;

use app\components\SearchModel;

class BoletimRgSearch extends SearchModel
{

    public $id;
    public $cliente_id;
    public $folha;
    public $bairro_id;
    public $bairro_quarteirao_id;
    public $bairro_quarteirao_numero;
    public $seq;
    public $data_cadastro;
    public $inserido_por;
    public $data;

    public function rules()
    {
        return [
            [['cliente_id', 'bairro_id', 'bairro_quarteirao_id', 'seq', 'folha'], 'integer'],
            [['data_cadastro', 'data', 'bairro_quarteirao_numero'], 'safe'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'cliente_id');
        $this->addCondition($query, 'folha');
        $this->addCondition($query, 'bairro_id');
        $this->addCondition($query, 'bairro_quarteirao_id');
        $this->addCondition($query, 'data_cadastro', true);
        $this->addCondition($query, 'inserido_por');
        $this->addCondition($query, 'data');

        if($this->bairro_quarteirao_numero) {
            $query->innerJoinWith([
                'quarteirao' => function ($query) {
                    $query->where("bairro_quarteiroes.numero_quarteirao LIKE '" . $this->bairro_quarteirao_numero . "%'");
                }
            ]);
        }
    }
}
