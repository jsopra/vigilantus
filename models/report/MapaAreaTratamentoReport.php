<?php
namespace app\models\report;

use app\models\Bairro;
use app\models\BairroQuarteirao;
use yii\base\Model;

class MapaAreaTratamentoReport extends Model
{
    public $bairro_id;
    public $quarteiroesComCasosAtivos;

    public function rules()
    {
        return [
            ['bairro_id', 'exist', 'targetClass' => Bairro::className(), 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'bairro_id' => 'Bairro',
        ];
    }

    public function load($data, $formName = null)
    {
        parent::load($data, $formName);
        
        $quarteiroes = BairroQuarteirao::find()->comFocosAtivos();
        
        if(is_numeric($this->bairro_id))
            $quarteiroes->doBairro($this->bairro_id);
        
        $this->quarteiroesComCasosAtivos = BairroQuarteirao::getCoordenadas($quarteiroes);
    }
}
