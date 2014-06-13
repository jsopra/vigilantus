<?php
namespace app\models\report;

use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\FocoTransmissor;
use app\models\EspecieTransmissor;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AreaTratamentoReport extends Model
{
    /*
     * filtros
     */
    public $bairro_id;
    public $lira;
    public $especie_transmissor_id;
    
    /*
     * resultados
     */
    public $dataProviderAreasFoco;
    public $dataProviderAreasTratamento;

    public function rules()
    {
        return [
            ['especie_transmissor_id', 'exist', 'targetClass' => EspecieTransmissor::className(), 'targetAttribute' => 'id'],
            ['bairro_id', 'exist', 'targetClass' => Bairro::className(), 'targetAttribute' => 'id'],
            ['lira', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'bairro_id' => 'Bairro',
            'lira' => 'LIRA',
            'especie_transmissor_id' => 'Espécie de Transmissor'
        ];
    }

    public function load($data, $formName = null)
    {
        parent::load($data, $formName);
        
        $this->_loadAreasDeFoco();
        $this->_loadAreasDeTratamento();
    }
    
    private function _loadAreasDeFoco() {
        
        $focos = FocoTransmissor::find();
        
        if(is_numeric($this->bairro_id))
            $focos->doBairro($this->bairro_id);
        
        if($this->lira != '' && $this->lira != null)
            $focos->doImovelLira(($this->lira ? true : false));
        
        if(is_numeric($this->especie_transmissor_id))
            $focos->daEspecieDeTransmissor($this->especie_transmissor_id);
        
        $focos->ativo();
        
        $this->dataProviderAreasFoco = new ActiveDataProvider(['query' => $focos]);
    }
    
    private function _loadAreasDeTratamento() {
       
        $lira = null;
        if($this->lira != '' && $this->lira != null)
            $lira = $this->lira ? true : false;
        
        $especieTransmissor = is_numeric($this->especie_transmissor_id) ? $this->especie_transmissor_id : null;
        
        $quarteiroes = BairroQuarteirao::find()->emAreaDeTratamento($lira, $especieTransmissor);
        
        if(is_numeric($this->bairro_id))
            $quarteiroes->doBairro($this->bairro_id);
        
        $this->dataProviderAreasTratamento = new ActiveDataProvider(['query' => $quarteiroes]);
    }
}
