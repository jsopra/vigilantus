<?php
namespace app\models;

use yii\db\ActiveQuery;

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