<?php

namespace app\components;

use Yii;
use yii\helpers\Json;
use \IntlDateFormatter;
use app\components\ActiveRecord;
use yii\db\Expression;

class PostgisActiveRecord extends ActiveRecord
{
    const TYPE_POLYGON = 'Polygon';
    const TYPE_POINT = 'Point';
    
    /**
     * Converte um array json em um comando insert de polígono postgis 
     * @param string $type Tipo do objeto postgis
     * @param json $coordenadas
     * @return \yii\db\Expression 
     */
    protected function jsonToPostgis($type, $coordenadas) {

        if($type != self::TYPE_POLYGON)
            return false;
        
        $arrayCoordenadas = Json::decode($coordenadas);
        
        $strPostgis = "ST_GeomFromText('POLYGON((";
        
        $coordenadasPostgis = [];
        foreach($arrayCoordenadas as $coordenada)
            $coordenadasPostgis[] = implode(' ', array_values($coordenada));
        
        if($coordenadasPostgis[0] != $coordenadasPostgis[count($coordenadasPostgis) - 1])
            $coordenadasPostgis[] = $coordenadasPostgis[0]; //fecha o polígono com o primeiro ponto
        
        $strPostgis .= implode(',', $coordenadasPostgis);
        
        $strPostgis .= "))', " . Yii::$app->params['postgisSRID'] . ")";

        return new Expression($strPostgis);
    }
    
    /**
     * Define latitude e longitude para o modelo, caso exista ponto válido cadastrado
     * @param string $type Tipo do objeto postgis
     * @param string $attributeToLoad Atributo a carregar do banco
     * @return mixed(false, array)
     */
    protected function postgisToArray($type, $attributeToLoad) {

        if(!in_array($type,[self::TYPE_POLYGON, self::TYPE_POINT]))
            return false;
        
        if(!$this->$attributeToLoad) 
            return false;
        
        switch($type) {
            case self::TYPE_POLYGON :
                return $this->_polygonToArray($this->id, $attributeToLoad);
                
            case self::TYPE_POINT : 
                return $this->_pointToArray($this->id, $attributeToLoad);
        }
        
        return false;
    } 
    
    /**
     * Converte um polígono postgis em array
     * @param int $id
     * @param string $attributeToLoad
     * @return mixed(boolean|array) 
     */
    private function _polygonToArray($id, $attributeToLoad) {
        
        $object = self::find()
            ->select('ST_asText(' . $attributeToLoad . ') as ' . $attributeToLoad)
            ->where(['id' => $id])
            ->one();
        
        if(!$object instanceof self)
            return false;
        
        if(strstr($object->$attributeToLoad, 'POLYGON') === false)
            return false;
        
        $coordenadas = explode(",",str_replace(['POLYGON(', ')', '('], '', $object->$attributeToLoad));
       
        if(count($coordenadas) == 0)
            return false;
        
        $arrayCoordenadas = [];
        
        foreach($coordenadas as $latLong)
            $arrayCoordenadas[] = explode(' ', $latLong);

        return $arrayCoordenadas; 
    }
    
    /**
     * Converte um polígono postgis em array
     * @param int $id
     * @param string $attributeToLoad
     * @return mixed(boolean|array) 
     */
    private function _pointToArray($id, $attributeToLoad) {
        
        $object = self::find()
            ->select('ST_asText(' . $attributeToLoad . ') as ' . $attributeToLoad)
            ->where(['id' => $id])
            ->one();

        if(!$object instanceof self)
            return false;
        
        if(strstr($object->$attributeToLoad, 'POINT') === false)
            return false;
        
        $coordenadas = explode(" ", str_replace(['POINT(', ')'], '', $object->coordenadas_area));
       
        if(count($coordenadas) == 0)
            return false;
               
        return $coordenadas;
    }
}