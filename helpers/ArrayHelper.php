<?php
namespace app\helpers;

use yii\helpers\ArrayHelper as YiiArrayHelper;

class ArrayHelper extends YiiArrayHelper
{
    /**
     * Cast all array values to integer, recursively
     * @param array $array
     * @return array
     */
    public static function castToInteger(array $array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::castToInteger($value);
            } else {
                $array[$key] = (int) $value;
            }
        }
        
        return $array;
    }
}
