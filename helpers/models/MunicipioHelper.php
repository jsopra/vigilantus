<?php
namespace app\helpers\models;

use Yii;
use yii\helpers\StringHelper as YiiStringHelper;
use yii\helpers\Json;
use app\models\Municipio;
use yii\helpers\Html;
use yii\helpers\Url;

class MunicipioHelper extends YiiStringHelper
{
    /**
     * Retorna brasão de município em tag html, se houver brasão
     * @param Municipio $municipio
     * @param string $tipo (mini, normal, large)
     * @return string
     */
    public static function getBrasaoAsImageTag(Municipio $municipio, $tipo = 'normal')
    {
        if(!$municipio->brasao) {
            return '-';
        }

        return Html::img(self::getBrasaoPath($municipio) . $tipo . '/' . $municipio->brasao);
    }

    /**
     * Retorna path de brasão
     * @param Municipio $municipio
     * @param boolean $base Default is false
     * @return string
     */
    public static function getBrasaoPath(Municipio $municipio, $internal = false)
    {
        $path = $internal ? Yii::$app->basePath . '/web' : Url::base();

        return $path . '/img/brasao/' . $municipio->sigla_estado . '/';
    }
}
