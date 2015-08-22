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

        $externalPath = self::getBrasaoPath($municipio);

        if(!$externalPath) {
            return '-';
        }

        return Html::img($externalPath . $tipo . '/' . $municipio->brasao);
    }

    /**
     * Retorna path de brasão
     * @param Municipio $municipio
     * @param boolean $base Default is false
     * @return string
     */
    public static function getBrasaoPath(Municipio $municipio, $internal = false)
    {
        $internalPath = Yii::$app->params['dataDir'] . '/brasao/' . $municipio->sigla_estado . '/';
        if($internal) {
            return $internalPath;
        }

        $externalPath = Yii::$app->params['publicDir'] . 'brasao/' . $municipio->sigla_estado . '/';
        if(!is_dir($externalPath)) {
            mkdir($externalPath);
        }

        foreach($municipio->brasaoSizes as $size) {

            $internalFile = $internalPath . $size[0] . '/' . $municipio->brasao;

            if(!is_dir($externalPath . $size[0])) {
                mkdir($externalPath . $size[0]);
            }

            $externalFile = $externalPath . $size[0] . '/' . $municipio->brasao;
            if(!is_file($externalFile)) {
                if(is_file($internalFile)) {
                    copy($internalFile, $externalFile);
                }
            }

        }

        return Url::base() . '/brasao/' . $municipio->sigla_estado . '/';
    }
}
