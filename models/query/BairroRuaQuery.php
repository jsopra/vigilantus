<?php
namespace app\models\query;

use yii\db\ActiveQuery;

class BairroRuaQuery extends ActiveQuery
{  
    public function doBairro($id) {
        $this->andWhere('bairro_id = :id', [':id' => $id]);
        return $this;
    }
    
    public function daRua($nome) {
        $this->andWhere('nome = :rua', [':rua' => $nome]);
        return $this;
    }
}