<?php
namespace app\models\indicador;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Municipio;
use app\models\EspecieTransmissor;
use app\models\FocoTransmissor;
use Yii;

class EvolucaoFocosReport extends Model
{
    public $especie_transmissor_id;

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
    
    public function getData() 
    {
        $municipio = Municipio::find()->one(); //FIX
        
        $anos = [
            date('Y') - 3,
            date('Y') - 2,
            date('Y') - 1,
            date('Y'),
        ];

        $meses = [
            1 => 'Jan',
            2 => 'Fev',
            3 => 'Mar',
            4 => 'Abr',
            5 => 'Mai',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Ago',
            9 => 'Set',
            10 => 'Out',
            11 => 'Nov',
            12 => 'Dez'
        ];

        $data = [['Mes']];

        $qtde = 0;

        foreach($anos as $indexMes => $ano) {
            $data[$qtde][] = (string) $ano;
        } 

        foreach($meses as $indexMes => $mes) {

            $qtde++;

            $subData = [];

            $subData[] = $mes;

            foreach($anos as $ano) {

                $model = FocoTransmissor::find()->doMes($indexMes)->doAno($ano);

                if($this->especie_transmissor_id) {
                    $model->daEspecieDeTransmissor($this->especie_transmissor_id);
                }

                $subData[] = $model->count();
            }

            $data[$qtde] = $subData;
        }

        return $data;
    }
}
