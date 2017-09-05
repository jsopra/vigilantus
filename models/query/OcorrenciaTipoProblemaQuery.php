<?php

namespace app\models\query;

use app\components\ActiveQuery;

class OcorrenciaTipoProblemaQuery extends ActiveQuery
{
	public function ativos()
	{
		$this->andWhere('ativo IS TRUE');
        return $this;
	}

    public function comNome($nome)
    {
        $this->andWhere("nome ILIKE '%" . trim($nome) . "%'");
        return $this;
    }

    public function naoAssociadoAoSetor($id)
    {
        $this->andWhere('id NOT IN (
            SELECT tipos_problemas_id from setor_tipos_ocorrencias WHERE setor_id = ' . $id . '
        )');
        return $this;
    }

    public function associadoUnicamenteAoSetor($id)
    {
        $this->andWhere('id NOT IN (
            SELECT tipos_problemas_id from setor_tipos_ocorrencias
        )');
        return $this;
    }
}
