<?php
namespace app\models\report;

use app\models\Bairro;
use app\models\BairroQuarteirao;
use yii\base\Model;

class MapaAreaTratamentoReport extends Model
{
    public $bairro_id;
    public $lira;
    public $quarteiroesComCasosAtivos;

    public function rules()
    {
        return [
            ['bairro_id', 'exist', 'targetClass' => Bairro::className(), 'targetAttribute' => 'id'],
            ['lira', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'bairro_id' => 'Bairro',
            'lira' => 'LIRA',
        ];
    }

    public function load($data, $formName = null)
    {
        parent::load($data, $formName);
        
        $lira = null;
        if($this->lira !== '')
            $lira = $this->lira ? true : false;
        
        $quarteiroes = BairroQuarteirao::find()->comFocosAtivos($lira);
        
        if(is_numeric($this->bairro_id))
            $quarteiroes->doBairro($this->bairro_id);
        
        $this->quarteiroesComCasosAtivos = BairroQuarteirao::getCoordenadas($quarteiroes);
    }
}
