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
     * @return string tag HTML ou string vazia.
     */
    public static function getBrasaoAsImageTag(Municipio $municipio, $tipo = 'normal')
    {
        if (!$municipio->brasao) {
            return '';
        }

        $externalPath = self::getBrasaoPath($municipio);

        if (!$externalPath) {
            return '';
        }

        return Html::img($externalPath . $tipo . '/' . $municipio->brasao);
    }

    /**
     * Retorna path de brasão
     * @param Municipio $municipio
     * @param boolean $diretorioUpload Se quer o diretório do upload ou a URL da pasta publicada
     * @return string
     */
    public static function getBrasaoPath(Municipio $municipio, $diretorioUpload = false)
    {
        $diretorioBrasoes = Yii::$app->params['diretorioUploadBrasoes'];
        $diretorioBrasoesEstado = $diretorioBrasoes . $municipio->sigla_estado . '/';

        if (!is_dir($diretorioBrasoes)) {
            mkdir($diretorioBrasoes);
        }
        if (!is_dir($diretorioBrasoesEstado)) {
            mkdir($diretorioBrasoesEstado);
        }
        $tamanhos = array_map(
            function($item) { return $item[0]; },
            $municipio->brasaoSizes
        );
        $tamanhos[] = 'original';
        foreach ($tamanhos as $tamanho) {
            $diretorioTamanho = $diretorioBrasoesEstado . '/' . $tamanho;
            if (!is_dir($diretorioTamanho)) {
                mkdir($diretorioTamanho);
            }
        }

        if ($diretorioUpload) {
            return $diretorioBrasoesEstado;
        }

        $assetManager = Yii::$app->assetManager;

        list($diretorio, $url) = $assetManager->publish($diretorioBrasoesEstado);

        return $url . '/';
    }
}
