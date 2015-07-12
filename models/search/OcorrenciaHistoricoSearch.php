<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * OcorrenciaHistoricoSearch represents the model behind the search form about OcorrenciaHistorico.
 */
class OcorrenciaHistoricoSearch extends SearchModel
{
    public $id;
    public $ocorrencia_id;
    public $tipo;

    public function rules()
    {
        return [
            [['id', 'ocorrencia_id', 'tipo'], 'integer'],
        ];
    }

    public function searchConditions($query)
    {
        $query->andFilterWhere([
            'id' => $this->id,
            'ocorrencia_id' => $this->ocorrencia_id,
            'tipo' => $this->tipo
        ]);
    }
}
