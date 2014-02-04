<?php
namespace app\models;

use yii\db\ActiveQuery;

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
            
            if ($usuario->municipio_id)
                $this->andWhere(['municipio_id' => $usuario->municipio_id]);
        }
        
        return $this;
    }
}