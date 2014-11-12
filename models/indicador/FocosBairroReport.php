<?php
namespace app\models\indicador;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bairro;
use app\models\EspecieTransmissor;
use app\models\FocoTransmissor;
use app\models\DepositoTipo;
use Yii;

class FocosBairroReport extends Model
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
        $bairros = Bairro::find()->orderBy('nome ASC')->all();

        $data = [];

        foreach($bairros as $bairro) {

            $model = FocoTransmissor::find()->doAno($this->ano)->doBairro($bairro->id);

            if($this->especie_transmissor_id) {
                $model->daEspecieDeTransmissor($this->especie_transmissor_id);
            }

            $data[] = [$bairro->nome, $model->count()];
        }

        return $data;
    }
}
