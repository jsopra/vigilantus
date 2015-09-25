<?php
namespace app\models\report;

use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\FocoTransmissor;
use app\models\EspecieTransmissor;
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
    public $inicio_periodo;
    public $fim_periodo;
    public $focos;

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
            [['inicio_periodo', 'fim_periodo'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'inicio_periodo' => 'Início Período',
            'fim_periodo' => 'Fim Período',
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

    public function loadAreasDeTratamento($cliente) {

        $lira = null;
        if($this->lira != '' && $this->lira != null)
            $lira = $this->lira ? true : false;

        $especieTransmissor = is_numeric($this->especie_transmissor_id) ? $this->especie_transmissor_id : null;

        $quarteiroes = BairroQuarteirao::find()->emAreaDeTratamento($cliente, $lira, $especieTransmissor);

        if(is_numeric($this->bairro_id)) {
            $quarteiroes->doBairro($this->bairro_id);
        }

        $this->dataProviderAreasTratamento = new ActiveDataProvider(['query' => $quarteiroes]);
    }

    /**
     * Geral URL para carregar mapa em KML
     * @return array
     */
    public function getUrlAreasFocos()
    {
        $url = ['kml/focos'];

        if(is_numeric($this->bairro_id)) {
            $url['bairroId'] = $this->bairro_id;
        }

        if($this->lira != '' && $this->lira != null) {
            $url['lira'] = ($this->lira ? true : false);
        }

        if(is_numeric($this->especie_transmissor_id)) {
            $url['especieId'] = $this->especie_transmissor_id;
        }

        if($this->inicio_periodo && $this->fim_periodo) {
            $url['inicio'] = $this->inicio_periodo;
            $url['fim'] = $this->fim_periodo;
        }

        return $url;
    }

}
