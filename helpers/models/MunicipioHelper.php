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
        if (!$municipio->brasao) {
            return '-';
        }

        $externalPath = self::getBrasaoPath($municipio);

        if (!$externalPath) {
            return '-';
        }

        return Html::img($externalPath . $tipo . '/' . $municipio->brasao);
    }

    /**
     * Retorna path de brasão
     * @param Municipio $municipio
     * @param boolean $diretorio Se quer o diretório ou a URL do diretório
     * @return string
     */
    public static function getBrasaoPath(Municipio $municipio, $diretorio = false)
    {
        $internalPath = Yii::$app->params['publicDir'] . '/img/brasao/' . $municipio->sigla_estado . '/';
        $externalPath = Yii::$app->params['publicDir'] . '/brasao/' . $municipio->sigla_estado . '/';

        if ($diretorio) {
            return $internalPath;
        }

        if (!is_dir($externalPath)) {
            mkdir($externalPath);
        }

        foreach ($municipio->brasaoSizes as $size) {
            $internalFile = $internalPath . $size[0] . '/' . $municipio->brasao;
            $externalFile = $externalPath . $size[0] . '/' . $municipio->brasao;

            if (!is_dir($externalPath . $size[0])) {
                mkdir($externalPath . $size[0]);
            }
            if (!is_file($externalFile) && is_file($internalFile)) {
                copy($internalFile, $externalFile);
            }

        }

        return Url::base() . '/brasao/' . $municipio->sigla_estado . '/';
    }
}
