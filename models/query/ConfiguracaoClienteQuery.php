<?php

namespace app\models\query;

use app\components\ActiveQuery;

class ConfiguracaoClienteQuery extends ActiveQuery
{
    public function doIdConfiguracao($id)
    {
        $this->andWhere('configuracao_id = :idconfiguracao', [':idconfiguracao' => $id]);
        return $this;
    }

    public function doCliente($id)
    {
        $this->andWhere('cliente_id = :idcliente', [':idcliente' => $id]);
        return $this;
    }

    public function doTipo($tipo)
    {
        $this->joinWith(['configuracao']);
        $this->andWhere('tipo = :tipo', [':tipo' => $tipo]);
        return $this;
    }
}
