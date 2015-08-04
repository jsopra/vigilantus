<?php

namespace app\models\query;

use app\components\ActiveQuery;

class OcorrenciaQuery extends ActiveQuery
{
    public function aberta()
    {
        $this->andWhere('data_fechamento IS NULL');
        return $this;
    }

    public function fechada()
    {
        $this->andWhere('data_fechamento IS NOT NULL');
        return $this;
    }

    public function anteriorA($dias)
    {
        $this->andWhere("data_criacao + interval '" . $dias . " days' >= coalesce(data_fechamento,CURRENT_DATE)");
        return $this;
    }

    public function entre($inicio, $fim)
    {
        $this->andWhere("CURRENT_DATE BETWEEN data_criacao + interval '" . $inicio . " days' AND data_criacao + interval '" . $fim . " days'  ");
        return $this;
    }

    public function posteriorA($dias)
    {
        $this->andWhere("data_criacao + interval '" . $dias . " days' < coalesce(data_fechamento,CURRENT_DATE)");
        return $this;
    }

    public function doNumeroControle($numero)
    {
        $this->andWhere('numero_controle = :numero_controle', [':numero_controle' => trim($numero)]);
        return $this;
    }

    public function criadaEm($mes, $ano)
    {
        $this->andWhere("EXTRACT(month from data_criacao) = :mesCriadoEm AND EXTRACT(year from data_criacao) = :anoCriadoEm", [':mesCriadoEm' => $mes, ':anoCriadoEm' => $ano]);
        return $this;
    }

    public function finalizadaEm($mes, $ano)
    {
        $this->andWhere("EXTRACT(month from data_fechamento) = :mesFechadoEm AND EXTRACT(year from data_fechamento) = :anoFechadoEm", [':mesFechadoEm' => $mes, ':anoFechadoEm' => $ano]);
        return $this;
    }

    public function criadaNoAno($ano)
    {
        $this->andWhere("EXTRACT(year from data_criacao) = :anoCriadoEm", [':anoCriadoEm' => $ano]);
        return $this;
    }

    public function finalizadaNoAno($ano)
    {
        $this->andWhere("EXTRACT(year from data_fechamento) = :anoFechadoEm", [':anoFechadoEm' => $ano]);
        return $this;
    }

    public function doProblema($id)
    {
        $this->andWhere('ocorrencia_tipo_problema_id = :idProblema', [':idProblema' => $id]);
        return $this;
    }

    public function doStatus($id)
    {
        $this->andWhere('status = :idStatus', [':idStatus' => $id]);
        return $this;
    }
}
