<?php
namespace app\models\query;

use app\models\Usuario;
use app\models\UsuarioRole;
use app\components\ActiveQuery;

class UsuarioRoleQuery extends ActiveQuery
{  
    /**
     * @param ActiveQuery $query
     * @param Usuario $usuario
     */
    public function doNivelDoUsuario(Usuario $usuario) {
        
        if ($usuario->role->id != UsuarioRole::ROOT)
            $this->andWhere('id <> ' . UsuarioRole::ROOT);
        
        return $this;
    }
}