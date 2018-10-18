<?php

namespace app\models;
use app\components\ActiveRecord;
use yii\db\Expression;
use \IntlDateFormatter;

/**
 * Este é a classe de modelo da tabela "amostras_transmissores".
 *
 * Estas são as colunas disponíveis na tabela "amostras_transmissores":
 * @property integer $id
 * @property string $data_criacao
 * @property string $data_atualizacao
 * @property string $data_coleta
 * @property integer $cliente_id
 * @property integer $tipo_deposito_id
 * @property integer $quarteirao_id
 * @property string $endereco
 * @property string $observacoes
 * @property integer $numero_casa
 * @property integer $numero_amostra
 * @property integer $quantidade_larvas
 * @property integer $quantidade_pupas
 * @property integer $visita_id
 */
class AmostraTransmissor extends ActiveRecord
{
	public $especie_transmissor_id;
	public $planilha_imovel_tipo_id;
	public $atualizado_por;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'amostras_transmissores';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['data_coleta'], 'date'],
			[['cliente_id', 'quarteirao_id'], 'required'],
			[['cliente_id', 'tipo_deposito_id', 'quarteirao_id', 'numero_casa', 'numero_amostra', 'quantidade_larvas', 'quantidade_pupas', 'visita_id'], 'integer'],
            [['visita_id'], 'exist', 'skipOnError' => true, 'targetClass' => VisitaImovel::className(), 'targetAttribute' => ['visita_id' => 'id']],
			[['endereco', 'observacoes'], 'string'],
			[['especie_transmissor_id', 'planilha_imovel_tipo_id', 'atualizado_por'], 'safe'],
			[['foco'], 'boolean'],
            ['planilha_imovel_tipo_id', 'required', 'when' => function($model) {
                return $model->foco == true;
            }],
            ['especie_transmissor_id', 'required', 'when' => function($model) {
                return $model->foco == true;
            }],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'data_criacao' => 'Data de Criação',
			'data_atualizacao' => 'Data de Atualização',
			'data_coleta' => 'Data da Coleta',
			'cliente_id' => 'Cliente',
			'tipo_deposito_id' => 'Tipo de Depósito',
			'quarteirao_id' => 'Quarteirão',
			'endereco' => 'Endereço',
			'observacoes' => 'Observações',
			'numero_casa' => 'Número da Casa',
			'numero_amostra' => 'Número da Amostra',
			'quantidade_larvas' => 'Quantidade de Larvas',
			'quantidade_pupas' => 'Quantidade de Pupas',
			'foco' => 'Foco',
			'especie_transmissor_id' => 'Espécie de Transmissor',
			'planilha_imovel_tipo_id' => 'Tipo de Imóvel',
			'foco_transmissor_id' => 'Referência de Foco',
			'visita_id' => 'Visita',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function extraFields()
	{
		return ['bairro'];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBairroQuarteirao()
	{
		return $this->hasOne(BairroQuarteirao::className(), ['id' => 'quarteirao_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getBairro()
	{
		return $this->hasOne(Bairro::className(), ['id' => 'bairro_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getVisita()
	{
		return $this->hasOne(VisitaImovel::className(), ['id' => 'visita_id']);
	}

	public function getTipoDeposito()
    {
        return $this->hasOne(DepositoTipo::className(), ['id' => 'tipo_deposito_id']);
    }

	public function save($runValidation = true, $attributes = NULL)
	{
		$formatter = new IntlDateFormatter(
            \Yii::$app->language,
            IntlDateFormatter::MEDIUM,
            IntlDateFormatter::NONE
        );

		$transaction = $this->getDb()->beginTransaction();

		try {

			$mudouValor = $this->isAttributeChanged('foco');

		    $result = parent::save($runValidation, $attributes);

		    if ($result) {

		    	if ($mudouValor && $this->foco == true) {

		    		$focosTransmissores = new FocoTransmissor;
		    		$focosTransmissores->data_coleta = date('Y-m-d', $formatter->parse($this->data_coleta));
		    		$focosTransmissores->data_entrada = date('Y-m-d', $formatter->parse($this->data_criacao));
		    		$focosTransmissores->data_exame = date('Y-m-d');
		    		$focosTransmissores->tipo_deposito_id = $this->tipo_deposito_id;
		    		$focosTransmissores->quantidade_forma_aquatica = $this->quantidade_larvas;
		    		$focosTransmissores->quantidade_forma_adulta = $this->quantidade_pupas;
		    		$focosTransmissores->bairro_quarteirao_id = $this->quarteirao_id;
		    		$focosTransmissores->cliente_id = $this->cliente_id;
		    		$focosTransmissores->planilha_endereco = $this->endereco . $this->numero_casa;
		    		$focosTransmissores->inserido_por = $this->atualizado_por;
		    		$focosTransmissores->atualizado_por = NULL;
		    		$focosTransmissores->especie_transmissor_id = $this->especie_transmissor_id;
		    		$focosTransmissores->quantidade_ovos = 0;
		    		$focosTransmissores->laboratorio = NULL;
		    		$focosTransmissores->tecnico = NULL;
		    		$focosTransmissores->imovel_id = NULL;
		    		$focosTransmissores->planilha_imovel_tipo_id = $this->planilha_imovel_tipo_id;

		    		if ($result = $focosTransmissores->save()) {
		    			$this->foco_transmissor_id = $focosTransmissores->id;
		    			$result = $this->save();
		    		}
		    	}

		    	if ($result) {
		        	$transaction->commit();
		        	return true;
		        }
		    }

		} catch (\Exception $e) {
		    $transaction->rollback();
		    throw $e;
		}

		$transaction->rollback();
		return false;
	}
}

