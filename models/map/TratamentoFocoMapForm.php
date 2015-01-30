<?php
namespace app\models\map;

use Yii;
use yii\base\Model;

class TratamentoFocoMapForm extends Model
{
    public $foco_id;

    public function rules()
    {
        return [
            [['foco_id'], 'required'],
            [['foco_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'foco_id' => 'Foco',
        ];
    }
}
