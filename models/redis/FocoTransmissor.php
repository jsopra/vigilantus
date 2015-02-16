<?php
namespace app\models\redis;

use app\components\RedisActiveRecord;
use app\models\EspecieTransmissor;
use app\models\BairroQuarteirao;

class FocoTransmissor extends RedisActiveRecord
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
            'quarteirao_coordenadas',
            'centro_quarteirao',
            'imovel_lira',
            'especie_transmissor_id',
            'cor_foco',
            'qtde_metros_area_foco',
            'informacao_publica',
        ];
    }

    /**
     * @return ActiveQuery defines a relation to the Order record (can be in other database, e.g. elasticsearch or sql)
     */
    public function getEspecieTransmissor()
    {
        return $this->hasOne(EspecieTransmissor::className(), ['id' => 'especie_transmissor_id']);
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
        return $this->hasOne(BairroQuarteirao::className(), ['id' => 'bairro_quarteirao_id']);
    }

    /**
     * Salva coordenadas do quarteirão com algorimo de serialização
     * @param array $value
     * @return void
     */
    public function setQuarteiraoCoordenadas($value)
    {
        $this->quarteirao_coordenadas = serialize($value);
    }

    /**
     * Salva coordenadas do centro do quarteirão com algorimo de serialização
     * @param array $value
     * @return void
     */
    public function setCentroQuarteirao($value)
    {
        $this->centro_quarteirao = serialize($value);
    }

    /**
     * Retorna coordenadas do quarteirão com algorimo de desserialização
     * @return array
     */
    public function getQuarteiraoCoordenadas()
    {
        return unserialize($this->quarteirao_coordenadas);
    }

    /**
     * Retorna coordenadas do centro do quarteirão com algorimo de serialização
     * @return array
     */
    public function getCentroQuarteirao()
    {
        return unserialize($this->centro_quarteirao);
    }
}
