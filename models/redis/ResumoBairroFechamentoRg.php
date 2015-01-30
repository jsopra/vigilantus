<?php
namespace app\models\redis;

use app\components\RedisActiveRecord;

class ResumoBairroFechamentoRg extends RedisActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        return [
            'id',
            'cliente_id',
            'bairro_id',
            'quantidade',
        ];
    }

    /**
     * @return ActiveQuery defines a relation to the Order record (can be in other database, e.g. elasticsearch or sql)
     */
    public function getBairro()
    {
        return $this->hasOne(Bairro::className(), ['id' => 'bairro_id']);
    }
}
