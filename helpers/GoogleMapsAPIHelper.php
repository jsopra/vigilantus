<?php
namespace app\helpers;

use yii\helpers\StringHelper as YiiStringHelper;
use \yii\helpers\Json;

class GoogleMapsAPIHelper extends YiiStringHelper
{
    /**
     * Converte array de coordenadas PHP em objeto JS para google maps
     * @param array $bounds
     * @return string
     */
    public static function arrayToBounds($bounds)
    {
        $str = '';
        $total = count($bounds);
        $i = 0;
        foreach($bounds as $latLong) {
            if(!isset($latLong[0]) || !isset($latLong[1]))
                continue;
            
            $str .= 'new google.maps.LatLng(' . $latLong[0] . ', ' . $latLong[1] . ')';
            
            $i++;
            
            if($i < $total)
                $str .= ', ';
        }

        return $str;
    }
    
    /**
     * Converte um json em objeto JS para google maps
     * @param string $json
     * @return string
     */
    public static function jsonToBounds($json)
    {
        $bounds = Json::decode($json);
        
        $array = [];
        
        foreach($bounds as $data)
            $array[] = array_values($data);
        
        $array[] = $bounds[0];
        
        return self::arrayToBounds($array);
    }
    
    /**
     * Converte um array para coordenadas json do googlem maps
     * @param array $array
     * @return string
     */
    public static function arrayToCoordinatesJson($array) {
        
        $newArray = [];
        
        foreach($array as $data)
            $newArray[] = [
                'A' => $data[0],
                'k' => $data[1]
            ];
        
        return Json::encode($newArray);
    }
}
