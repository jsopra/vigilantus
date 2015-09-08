<?php
namespace app\models\query;

use app\models\Usuario;
use app\models\UsuarioRole;
use app\components\ActiveQuery;

class UsuarioQuery extends ActiveQuery
{

    public function ativo() {
        $this->andWhere('excluido IS FALSE');
        return $this;
    }

    /**
     * @param ActiveQuery $query
     */
    public function excluido() {
        $this->andWhere('excluido IS TRUE');
        return $this;
    }

    /**
     * @param ActiveQuery $query
     * @param Usuario $usuario
     */
    public function doNivelDoUsuario(Usuario $usuario) {

        if ($usuario->role->id != UsuarioRole::ROOT) {

            $this->andWhere('usuario_role_id <> ' . UsuarioRole::ROOT);

            if ($usuario->cliente_id) {
                $this->andWhere(['cliente_id' => $usuario->cliente_id]);
            }
        }

        return $this;
    }

    public function naoAssociadoAoSetor($id)
    {
        $this->andWhere('id NOT IN (
            SELECT usuario_id from setor_usuarios where setor_id = ' . $id . '
        )');
        return $this;
    }
}
