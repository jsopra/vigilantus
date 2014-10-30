<?php
namespace app\models\report;

use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\FocoTransmissor;
use app\models\EspecieTransmissor;
use app\models\Municipio;
use app\models\redis\FocoTransmissor as FocoTransmissorRedis;
use yii\base\Model;
use yii\data\ArrayDataProvider;

class FocosBairroReport extends Model
{
    /*
     * filtros
     */
    public $especie_transmissor_id;
    public $ano;
    
    /*
     * resultados
     */
    public $dataProviderAreasFoco;

    public function rules()
    {
        return [
            ['especie_transmissor_id', 'exist', 'targetClass' => EspecieTransmissor::className(), 'targetAttribute' => 'id'],
            ['ano', 'required'],
            ['ano', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ano' => 'Ano',
            'lira' => 'LIRA',
            'especie_transmissor_id' => 'Espécie de Transmissor'
        ];
    }

    public function load($data, $formName = null)
    {
        parent::load($data, $formName);

        $data = [];

        $bairros = Bairro::find()->orderBy('nome ASC')->all();

        foreach($bairros as $bairro) {

            $subData = [];
            $total = 0;

            $subData[] = $bairro->nome;

            $focos = FocoTransmissor::find();
            if(is_numeric($this->especie_transmissor_id)) {
                $focos->daEspecieDeTransmissor($this->especie_transmissor_id);
            }
            $focos->doAno($this->ano)->doBairro($bairro->id)->porMes();
              
            $records = $focos->all();

            for($i = 1; $i <= 12; $i++) {

                foreach($records as $record) {
                    if($record->mes == $i) {
                        $total += $subData[$i] = $record->quantidade_registros;
                        break;
                    }
                }

                if(!isset($subData[$i])) {
                    $subData[$i] = 0;
                }
            }

            $subData[] = $total;
            $subData[] = $bairro->id;
            $subData[] = $this->ano;
            $subData[] = $this->especie_transmissor_id;

            $data[] = $subData;
        }      

        $this->dataProviderAreasFoco = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => Bairro::find()->count(),
            ],
        ]);
    }
    
}
