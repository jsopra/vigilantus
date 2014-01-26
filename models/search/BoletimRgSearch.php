<?php

namespace app\models\search;

use app\components\SearchModel;

class BoletimRgSearch extends SearchModel
{

    public $id;
    public $municipio_id;
    public $folha;
    public $ano;
    public $bairro_id;
    public $bairro_quarteirao_id;
    public $seq;
    public $data_cadastro;
    public $inserido_por;
    public $mes;

    public function rules()
    {
        return [
            [['municipio_id', 'bairro_id', 'bairro_quarteirao_id', 'seq', 'folha', 'mes', 'ano'], 'required'],
        ];
    }

    public function searchConditions($query)
    {
        $this->addCondition($query, 'id');
        $this->addCondition($query, 'municipio_id');
        $this->addCondition($query, 'folha', true);
        $this->addCondition($query, 'ano');
        $this->addCondition($query, 'bairro_id');
        $this->addCondition($query, 'quarteirao_id');
        $this->addCondition($query, 'data_cadastro', true);
        $this->addCondition($query, 'inserido_por');
        $this->addCondition($query, 'mes');
    }
}
