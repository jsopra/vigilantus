<?php
namespace app\helpers;

use Yii;
use yii\helpers\StringHelper as YiiStringHelper;
use yii\helpers\Json;

class MapHelper extends YiiStringHelper
{
    const SRID_SPHERICAL_MERCATOR = 4238;

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

    public static function getArrayCoordenadas($coordenadas)
    {
        $arrayCoordenadas = [];
        foreach($coordenadas as $coordenada) {

            $arrayCoordenadas[] = [(double) $coordenada[1], (double) $coordenada[0]];
        }

        return Json::encode($arrayCoordenadas);
    }
}
