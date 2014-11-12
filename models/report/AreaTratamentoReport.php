<?php
namespace app\models\report;

use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\FocoTransmissor;
use app\models\EspecieTransmissor;
use app\models\Municipio;
use app\models\redis\FocoTransmissor as FocoTransmissorRedis;
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
    }
    
    public function loadAreasDeFoco() {
        
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
    
    public function loadAreasDeTratamento() {
       
        $lira = null;
        if($this->lira != '' && $this->lira != null)
            $lira = $this->lira ? true : false;
        
        $especieTransmissor = is_numeric($this->especie_transmissor_id) ? $this->especie_transmissor_id : null;
        
        $quarteiroes = BairroQuarteirao::find()->emAreaDeTratamento($lira, $especieTransmissor);
        
        if(is_numeric($this->bairro_id))
            $quarteiroes->doBairro($this->bairro_id);
        
        $this->dataProviderAreasTratamento = new ActiveDataProvider(['query' => $quarteiroes]);
    }
    
    /**
     * Carrega áreas de foco para o mapa
     * @return FocosAtivos[] 
     */
    public function loadAreasDeFocoMapa()
    {
        $focos = FocoTransmissorRedis::find();
        
        $municipio = Municipio::find()->one(); //fix
        
        $focos->doMunicipio($municipio->id);
        
        if(is_numeric($this->bairro_id))
            $focos->doBairro($this->bairro_id);
        
        if($this->lira != '' && $this->lira != null)
            $focos->doImovelLira(($this->lira ? true : false));
        
        if(is_numeric($this->especie_transmissor_id))
            $focos->daEspecieDeTransmissor($this->especie_transmissor_id);

        return $focos->all();
    }
}
