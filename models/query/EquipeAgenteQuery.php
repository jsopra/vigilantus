<?php

namespace app\models\query;

use app\components\ActiveQuery;

class EquipeAgenteQuery extends ActiveQuery
{
    public function daEquipe($id) {
        $this->andWhere('equipe_id = :id', [':id' => $id]);
        return $this;
    }

    public function queNao($id) {
        $this->andWhere('id <> :quenao', [':quenao' => $id]);
        return $this;
    }

    public function doNome($nome)
    {
        $this->andWhere('nome = :nome', [':nome' => trim($nome)]);
        return $this;
    }

    public function doUsuario($id)
    {
        $this->andWhere('usuario_id = :usuarioId', [':usuarioId' => $id]);
        return $this;
    }
}
