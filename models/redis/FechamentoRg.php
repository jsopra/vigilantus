<?php
namespace app\models\redis;

use app\components\RedisActiveRecord;

class FechamentoRg extends RedisActiveRecord
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
            'bairro_quarteirao_id', 
            'boletim_rg_id',
            'data',
            'quantidade', 
            'lira',
            'imovel_tipo_id',
            'quantidade_foco'
        ];
    }
    
    /**
     * @return ActiveQuery defines a relation to the Order record (can be in other database, e.g. elasticsearch or sql)
     */
    public function getBoletimRg()
    {
        return $this->hasOne(BoletimRg::className(), ['id' => 'boletim_rg_id']);
    }

    /**
     * @return ActiveQuery defines a relation to the Order record (can be in other database, e.g. elasticsearch or sql)
     */
    public function getImovelTipo()
    {
        return $this->hasOne(EspecieTransmissor::className(), ['id' => 'imovel_tipo_id']);
    }
    
    /**
     * @return ActiveQuery defines a relation to the Order record (can be in other database, e.g. elasticsearch or sql)
     */
    public function getBairro()
    {
        return $this->hasOne(Bairro::className(), ['id' => 'bairro_id']);
    }
    
    /**
     * @return ActiveQuery defines a relation to the Order record (can be in other database, e.g. elasticsearch or sql)
     */
    public function getBairroQuarteirao()
    {
        return $this->hasOne(BairroQuarteirao::className(), ['id' => 'quarteirao_id']);
    }
}