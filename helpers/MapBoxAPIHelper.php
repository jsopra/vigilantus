<?php
namespace app\helpers;

use Yii;
use yii\helpers\StringHelper as YiiStringHelper;
use yii\helpers\Json;

class MapBoxAPIHelper extends YiiStringHelper
{
    /**
     * Scripts do mapbox e plugins diversos
     * @var array
     */
    private static $_scripts = [
        'default' => [
            'js' => ['https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.js'],
            'css' => ['https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.css']
        ],
        'drawing' => [
            'js' => [
                'https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-geodesy/v0.1.0/leaflet-geodesy.js',
                'https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.2.2/leaflet.draw.js'
            ],
            'css' => ['https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.2.2/leaflet.draw.css'],
        ],
        'fullScreen' => [
            'js' => ['https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.3/Leaflet.fullscreen.min.js'],
            'css' => ['https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.3/leaflet.fullscreen.css'],
        ],
        'minimap' =>
        [
            'js' => ['https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-minimap/v1.0.0/Control.MiniMap.js'],
            'css' => ['https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v0.0.3/leaflet.fullscreen.css'],
        ],
        'omnivore' =>
        [
            'js' => ['https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.2.0/leaflet-omnivore.min.js'],
            'css' => [],
        ]
    ];

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
     * @param array $plugins
     * @param boolean $useDefault
     * @return void
     */
    public static function registerScript($view, $plugins = [], $useDefault = true)
    {
        if($useDefault) {
            self::_registerScriptFiles($view, 'default');
        }

        if(isset($plugins['default'])) {
            unset($plugins['default']);
        }

        foreach($plugins as $plugin) {

            if(!isset(self::$_scripts[$plugin])) {
                continue;
            }

            self::_registerScriptFiles($view, $plugin);
        }

        return;
    }

    /**
     * Register script files into view file
     * @return void
     */
    private static function _registerScriptFiles($view, $plugin)
    {
        foreach(self::$_scripts[$plugin]['js'] as $script) {
            $view->registerJsFile($script);
        }

        foreach(self::$_scripts[$plugin]['css'] as $style) {
            $view->registerCssFile($style);
        }

        return;
    }
}
