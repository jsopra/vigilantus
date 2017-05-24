<?php
namespace app\helpers\models;

use Yii;
use yii\helpers\StringHelper as YiiStringHelper;
use yii\helpers\Json;
use app\models\Municipio;
use yii\helpers\Html;
use yii\helpers\Url;
use Exception;
use app\helpers\ImageHelper;
use yii\imagine\Image;

class MunicipioHelper extends YiiStringHelper
{
    /**
     * Retorna brasão de município em URL, se houver brasão
     * @param Municipio $municipio
     * @param string $tipo (mini, normal, large)
     * @return string url ou string vazia.
     */
    public static function getBrasaoUrl(Municipio $municipio, $tipo = 'normal')
    {
        if (!$municipio->brasao) {
            return '';
        }

        return Yii::$app->get('s3')->getUrl('brasao/' . $tipo . '_' . $municipio->brasao);
    }

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

        return Html::img(Yii::$app->get('s3')->getUrl('brasao/' . $tipo . '_' . $municipio->brasao));
    }

    /**
     * Faz o upload de um brasão ao S3 com diferentes tipos de tamanhos
     * @param Municipio $municipio
     * @param UploadedFile $file Objeto de upload
     * @throws Upload exceptions
     * @return void
     */
    public static function saveBrasao(Municipio $municipio, yii\web\UploadedFile $file)
    {
        $s3 = Yii::$app->get('s3');

        $pathToFile = getenv('UPLOADS_DIR') . $file->baseName . '.' . $file->extension;

        if (!$file->saveAs($pathToFile, false)) {
            throw new \Exception('Erro ao salvar arquivo em disco');
        }

        if (!$s3->put('brasao/original_' . $municipio->id . '.' . $file->extension, file_get_contents($pathToFile))) {
            throw new \Exception('Erro ao salvar arquivo original no S3');
        }

        list($originalWidth, $originalHeight) = getimagesize($pathToFile);

        foreach ($municipio->brasaoSizes as $size) {

            $folder = $size[0];
            $width = $size[1];
            $height = $size[2];

            $size = ImageHelper::calculateDimensions($originalWidth, $originalHeight, $width, $height);

            $image = Image::thumbnail($pathToFile, $size['width'], $size['height']);

            $pathToFileResized = getenv('UPLOADS_DIR') . $file->baseName . '_' . $folder . '.' . $file->extension;

            if (!$image->save($pathToFileResized)) {
                throw new \Exception('Erro ao salvar arquivo redimensionado em disco');
            }

            if (!$s3->put('brasao/' . $folder . '_' . $municipio->id . '.' . $file->extension, file_get_contents($pathToFileResized))) {
                throw new \Exception('Erro ao salvar arquivo redimensionado no S3');
            }

            if (is_file($pathToFileResized)) {
                unset($pathToFileResized);
            }
        }

        if (is_file($pathToFile)) {
            unset($pathToFile);
        }

        return;
    }
}
