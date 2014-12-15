<?php
namespace app\helpers\models;

use Yii;
use yii\helpers\StringHelper as YiiStringHelper;
use app\models\FocoTransmissor;

class EspecieTransmissorHelper extends YiiStringHelper
{
    /**
     * Busca lista de doenças, caso houver
     * @param string $model
     * @return string|null
     */
    public static function doencasToHtml($model)
    {
        $str = '<ul>';
        foreach($model->doencasEspecie as $doenca) {
            $str .= '<li>' . $doenca->doenca->nome . '</li>';
        }
        $str .= '</ul>';

        return $str;
    }
}
