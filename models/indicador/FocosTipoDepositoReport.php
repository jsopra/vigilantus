<?php
namespace app\models\indicador;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Municipio;
use app\models\EspecieTransmissor;
use app\models\FocoTransmissor;
use app\models\DepositoTipo;
use Yii;

class FocosTipoDepositoReport extends Model
{
    public $ano;
    public $especie_transmissor_id;

    public function rules()
    {
        return [
            ['ano', 'required'],
            ['ano', 'integer'],
            ['especie_transmissor_id', 'exist', 'targetClass' => EspecieTransmissor::className(), 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ano' => 'Ano',
            'especie_transmissor_id' => 'Espécie de Transmissor',
        ];
    }
    
    public function getData() 
    {
        $municipio = Municipio::find()->one(); //FIX
        

        $tiposDeposito = DepositoTipo::find()->all();

        $data = [];
        $total = 0;

        foreach($tiposDeposito as $tipo) {

            $model = FocoTransmissor::find()->doAno($this->ano)->doTipoDeposito($tipo->id);

            if($this->especie_transmissor_id) {
                $model->daEspecieDeTransmissor($this->especie_transmissor_id);
            }

            $total += $qtde = $model->count();

            $data[] = [$tipo->descricao, $qtde, $tipo->sigla];
        }

        foreach($data as $index => $row) {

        	if($index == 0) {
        		continue;
        	}

        	$data[$index][1] = $total > 0 ? round((($data[$index][1] * 100) / $total),2) : 0;
        }

        return $data;
    }
}
