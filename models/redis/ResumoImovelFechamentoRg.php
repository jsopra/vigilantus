<?php
namespace app\models\redis;

use app\components\RedisActiveRecord;

class ResumoImovelFechamentoRg extends RedisActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        return [
            'id',
            'cliente_id',
            'imovel_tipo_id',
            'quantidade',
        ];
    }

    /**
     * @return ActiveQuery defines a relation to the Order record (can be in other database, e.g. elasticsearch or sql)
     */
    public function getImovelTipo()
    {
        return $this->hasOne(EspecieTransmissor::className(), ['id' => 'imovel_tipo_id']);
    }
}
