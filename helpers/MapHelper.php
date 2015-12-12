<?php
namespace app\helpers;

use Yii;
use yii\helpers\StringHelper as YiiStringHelper;
use yii\helpers\Json;

class MapHelper extends YiiStringHelper
{
    const SRID_SPHERICAL_MERCATOR = 4238;

    public static function getArrayCoordenadas($coordenadas)
    {
        $arrayCoordenadas = [];
        foreach($coordenadas as $coordenada) {
            $arrayCoordenadas[] = [(double) $coordenada[1], (double) $coordenada[0]];
        }

        return Json::encode($arrayCoordenadas);
    }

    public static function jsonToCoordinatesArray($json)
    {
        $return = [];
        return $data = Json::decode($json);
        foreach($data as $coordenada) {
            $return[] = [(double) $coordenada[1], (double) $coordenada[0]];
        }

        return $return;
    }
}
