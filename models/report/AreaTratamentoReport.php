<?php
namespace app\models\report;

use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\FocoTransmissor;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AreaTratamentoReport extends Model
{
    /*
     * filtros
     */
    public $bairro_id;
    public $lira;
    
    /*
     * resultados
     */
    public $quarteiroesComCasosAtivos;
    public $dataProviderAreasFoco;
    public $dataProviderAreasTratamento;

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
        
        $this->_loadMapa();
        $this->_loadAreasDeFoco();
        $this->_loadAreasDeTratamento();
    }
    
    /**
     * Carrega dados do mapa (primeira aba) 
     */
    private function _loadMapa() {
        
        $lira = null;
        if($this->lira != '' && $this->lira != null)
            $lira = $this->lira ? true : false;
        
        $quarteiroes = BairroQuarteirao::find()->comFocosAtivos($lira);
        
        if(is_numeric($this->bairro_id))
            $quarteiroes->doBairro($this->bairro_id);
        
        $this->quarteiroesComCasosAtivos = BairroQuarteirao::getCoordenadas($quarteiroes);
    }
    
    /**
     * Carrega dados de foco (terceira aba) 
     */
    private function _loadAreasDeFoco() {
        
        $focos = FocoTransmissor::find();
        
        if(is_numeric($this->bairro_id))
            $focos->doBairro($this->bairro_id);
        
        if($this->lira != '' && $this->lira != null)
            $focos->doImovelLira(($this->lira ? true : false));
        
        $this->dataProviderAreasFoco = new ActiveDataProvider(['query' => $focos]);
    }
    
    /**
     * Carrega dados de área de tratamento (segunda aba) 
     */
    private function _loadAreasDeTratamento() {
       
        $lira = null;
        if($this->lira != '' && $this->lira != null)
            $lira = $this->lira ? true : false;
        
        $quarteiroes = BairroQuarteirao::find()->emAreaDeTratamento($lira);
        
        if(is_numeric($this->bairro_id))
            $quarteiroes->doBairro($this->bairro_id);
        
        $this->dataProviderAreasTratamento = new ActiveDataProvider(['query' => $quarteiroes]);
    }
}
