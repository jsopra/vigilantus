<?php
namespace app\models\report;

use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\FocoTransmissor;
use app\models\EspecieTransmissor;
use app\models\redis\FocoTransmissor as FocoTransmissorRedis;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class FocosReport extends Model
{
    /*
     * filtros
     */
    public $bairro_id;
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
            ['bairro_id', 'exist', 'targetClass' => Bairro::className(), 'targetAttribute' => 'id'],
            ['ano', 'required'],
            ['ano', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ano' => 'Ano',
            'bairro_id' => 'Bairro',
            'lira' => 'LIRA',
            'especie_transmissor_id' => 'Espécie de Transmissor'
        ];
    }

    public function load($data, $formName = null)
    {
        parent::load($data, $formName);
        
        $focos = FocoTransmissor::find();
        
        if(is_numeric($this->bairro_id)) {
            $focos->doBairro($this->bairro_id);
        }
        
        if(is_numeric($this->especie_transmissor_id)) {
            $focos->daEspecieDeTransmissor($this->especie_transmissor_id);
        }

        $focos->doAno($this->ano);
        
        $focos->orderBy('data_coleta ASC');
        
        $this->dataProviderAreasFoco = new ActiveDataProvider(['query' => $focos]);
    }
    
}
