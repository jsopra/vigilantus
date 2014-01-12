<?php

namespace app\components\validators;

use yii\validators\DateValidator as YiiDateValidator;

class DateValidator extends YiiDateValidator
{
    /**
     * @var boolean
     */
    public $format = false;

    /**
     * @var boolean
     */
    public $time = false;
    
    /**
     * @var boolean
     */
    public $formatted = false;

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    public function validateAttribute($object, $attribute)
    {
        if (!$this->format) {
            
            $dateFormat = $timeFormat = null;
            
            if ($this->formatted) {
                $dateFormat = \Yii::$app->locale->getDateFormat('medium');
                $timeFormat = strtr(
                    \Yii::$app->locale->dateTimeFormat,
                    [
                        "{0}" => \Yii::$app->locale->getTimeFormat('medium'),
                        "{1}" => \Yii::$app->locale->getDateFormat('medium')
                    ]
                );
            } else {
                $dateFormat = 'Y-m-d';
                $timeFormat = 'Y-m-d H:i:s';
            }

            $this->format = ($this->time) ? $timeFormat : $dateFormat;
        }

        return parent::validateAttribute($object, $attribute);
    }

    /**
     * Valida uma data sem estar atrelada a um objeto
     * @param string $value
     * @return boolean
     */
    public function validateValue($value)
    {
        if ($this->skipOnEmpty && $this->isEmpty($value)) {
            return;
        }

        if (!$this->format) {
            
            $dateFormat = \Yii::$app->locale->getDateFormat('medium');
            
            $timeFormat = strtr(
                \Yii::$app->locale->dateTimeFormat,
                [
                    "{0}" => \Yii::$app->locale->getTimeFormat('medium'),
                    "{1}" => \Yii::$app->locale->getDateFormat('medium')
                ]
            );
            
            $this->format = ($this->time) ? $timeFormat : $dateFormat;
        }

        $formats = is_string($this->format) ? [$this->format] : $this->format;
        $valid = false;
        
        foreach ($formats as $format) {
            
            $fakeFormat = preg_replace('/[A-Za-z]/', '9', $format);
            $fakeValue = preg_replace('/[0-9]/', '9', $value);

            if ($fakeFormat == $fakeValue) {

                $timestamp = CDateTimeParser::parse(
                    $value,
                    $format,
                    [
                        'month' => 1,
                        'day' => 1,
                        'hour' => 0,
                        'minute' => 0,
                        'second' => 0
                    ]
                );

                if ($timestamp !== false) {
                    $valid = true;
                    break;
                }
            }
        }

        return $valid;
    }
}
