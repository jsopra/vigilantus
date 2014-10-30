<?php
namespace app\models\indicador;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EspecieTransmissor;
use app\models\FocoTransmissor;
use app\models\Bairro;
use Yii;

class ResumoFocosReport extends Model
{
    public $especie_transmissor_id;
    private $_anos;

    public function rules()
    {
        return [
            ['especie_transmissor_id', 'exist', 'targetClass' => EspecieTransmissor::className(), 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'especie_transmissor_id' => 'Espécie de Transmissor',
        ];
    }

    public function load($data, $formName = null)
    {
        parent::load($data, $formName);
        
        $this->_anos = [
            date('Y') - 5,
            date('Y') - 4,
            date('Y') - 3,
            date('Y') - 2,
            date('Y') - 1,
            date('Y'),
        ];
    }
    
    public function getData() 
    {
        $data = [['Ano', 'Número de Focos']];

        foreach($this->_anos as $ano) {

            $model = FocoTransmissor::find()->doAno($ano);

            if($this->especie_transmissor_id) {
                $model->daEspecieDeTransmissor($this->especie_transmissor_id);
            }

            $data[] = [(string) $ano, $model->count()];
        }

        return $data;
    }

    public function getDataPercentual() 
    {
        $data = [['Ano', '% Bairros com Foco']];

        $totalBairros = Bairro::find()->count();
        $bairros = Bairro::find()->all();

        foreach($this->_anos as $ano) {

            $positivados = Bairro::find()->comFoco($ano, $this->especie_transmissor_id)->count();

            $percentual = $totalBairros > 0 ? round((($positivados * 100) / $totalBairros),2) : 0;

            $data[] = [(string) $ano, $percentual];
        }

        return $data;
    }
}
