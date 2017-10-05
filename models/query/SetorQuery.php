<?php

namespace app\models\query;

use app\components\ActiveQuery;
use app\models\Ocorrencia;

class SetorQuery extends ActiveQuery
{
    public function queNao($id)
    {
        $this->andWhere('id <> :id', [':id' => $id]);
        return $this;
    }

    public function trazerSetorTipoProblema(Ocorrencia $model)
    {
        if ($model->ocorrencia_tipo_problema_id == null) {
            $this->andWhere('padrao_ocorrencias = TRUE');
        } else {
            $this->andWhere('id IN (SELECT setor_id FROM setor_tipos_ocorrencias WHERE tipos_problemas_id = '. $model->ocorrencia_tipo_problema_id . ')');
        }

        return $this;
    }

    public function padraoParaOcorrencias()
    {
        $this->andWhere('padrao_ocorrencias IS TRUE');
        return $this;
    }

    public function doUsuario($usuario)
    {
        $setoresDoUsuario = $usuario->getIdsSetores();
        if (count($setoresDoUsuario) == 0) {
            return $this;
        }

        $this->andWhere("id IN (" . implode(',', $setoresDoUsuario) . ")");

        return $this;
    }
}
