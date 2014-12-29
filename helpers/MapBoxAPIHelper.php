<?php
namespace app\helpers;

use Yii;
use yii\helpers\StringHelper as YiiStringHelper;
use yii\helpers\Json;

class MapBoxAPIHelper extends YiiStringHelper
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

    /**
     * Registra scripts do MapBox
     * @param Object $view
     * @return true
     */
    public static function registerScripts($view)
    {
        $view->registerJsFile('https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.js');
        $view->registerCssFile('https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.css');

        return true;
    }

    /**
     * Registra scripts do MapBox
     * @param Object $view
     * @return true
     */
    public static function registerDrawingScripts($view)
    {
        $view->registerJsFile('https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.2.2/leaflet.draw.js');
        $view->registerJsFile('https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-geodesy/v0.1.0/leaflet-geodesy.js');
        $view->registerCssFile('https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.2.2/leaflet.draw.css');

        return true;
    }

    public static function registerFullWindowScripts($view)
    {
        $view->registerJsFile('https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.3/Leaflet.fullscreen.min.js');
        $view->registerCssFile('https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.3/leaflet.fullscreen.css');

        return true;
    }

    public static function registerMinimapScripts($view)
    {
        $view->registerJsFile('https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-minimap/v1.0.0/Control.MiniMap.js');
        $view->registerCssFile('https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-minimap/v1.0.0/Control.MiniMap.css');

        return true;
    }
}
