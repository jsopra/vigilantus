<?php
namespace app\helpers;

use Yii;
use yii\helpers\StringHelper as YiiStringHelper;

class ImageHelper extends YiiStringHelper
{
    public static function calculateDimensions($width,$height,$maxwidth,$maxheight)
	{
        if($width != $height) {

            if($width > $height) {

                $t_width = $maxwidth;
                $t_height = (($t_width * $height)/$width);
                //fix height
                if($t_height > $maxheight) {

                    $t_height = $maxheight;
                    $t_width = (($width * $t_height)/$height);
                }
            }
            else {

                $t_height = $maxheight;
                $t_width = (($width * $t_height)/$height);
                //fix width
                if($t_width > $maxwidth) {
                	
                    $t_width = $maxwidth;
                    $t_height = (($t_width * $height)/$width);
                }
            }
        }
        else {
            $t_width = $t_height = min($maxheight,$maxwidth);
        }

        return [
        	'height' => (int) $t_height,
        	'width' => (int) $t_width
        ];
	}
}