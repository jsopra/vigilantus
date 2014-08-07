<?php
namespace app\models\query;

use Yii;
use app\components\ActiveQuery;

class ClienteModuloQuery extends ActiveQuery
{  
    public function doCliente($id) {
        $this->andWhere('cliente_id = :id', [':id' => $id]);
        return $this;
    }
}