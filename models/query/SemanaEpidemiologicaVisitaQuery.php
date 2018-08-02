<?php

namespace app\models\query;

use app\components\ActiveQuery;
use app\models\VisitaStatus;

class SemanaEpidemiologicaVisitaQuery extends ActiveQuery
{
    public function daSemanaEpidemiologica($id)
    {
        $this->andWhere('semana_epidemiologica_id = :idCiclo', [':idCiclo' => $id]);
        return $this;
    }

    public function doAgente($id)
    {
        $this->andWhere('agente_id = :idAgente', [':idAgente' => $id]);
        return $this;
    }

    public function realizada()
    {
        $this->andWhere('visita_status_id = :visitaRealizada', [':visitaRealizada' => VisitaStatus::CONCLUIDA]);
        return $this;
    }
}
