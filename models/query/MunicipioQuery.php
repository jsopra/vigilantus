<?php
namespace app\models\query;

use Yii;
use app\components\ActiveQuery;

class MunicipioQuery extends ActiveQuery
{
    public function porEstado()
    {
        return $this->orderBy('sigla_estado');
    }

    public function ordemAlfabetica()
    {
        return $this->orderBy('nome');
    }

    public function clientes()
    {
        return $this->andWhere('id IN (SELECT municipio_id FROM clientes)');
    }
}
