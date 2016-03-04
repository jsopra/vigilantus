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
 */
class AmostraTransmissor extends ActiveRecord
{
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
			[['data_coleta'], 'date', 'time' => true],
			[['cliente_id', 'tipo_deposito_id', 'quarteirao_id', 'observacoes'], 'required'],
			[['cliente_id', 'tipo_deposito_id', 'quarteirao_id', 'numero_casa', 'numero_amostra', 'quantidade_larvas', 'quantidade_pupas'], 'integer'],
			[['endereco', 'observacoes'], 'string'],
			[['foco'], 'boolean'],
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
		return $this->hasOne(Bairro::className(), ['id' => 'bairro_id'])
            ->via('bairroQuarteirao');
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
		    		$focosTransmissores->data_coleta = date('Y-m-d', $formatter->parse($row->getValue('data_coleta')));
		    		$focosTransmissores->data_entrada = $this->data_criacao;
		    		$focosTransmissores->tipo_deposito_id = $this->tipo_deposito_id;
		    		$focosTransmissores->quantidade_forma_aquatica = $this->quantidade_larvas;
		    		$focosTransmissores->quantidade_forma_adulta = $this->quantidade_pupas;
		    		$focosTransmissores->bairro_quarteirao_id = $this->quarteirao_id;
		    		$focosTransmissores->cliente_id = $this->cliente_id;
		    		$focosTransmissores->planilha_endereco = $this->endereco . $this->numero_casa;
		    		$focosTransmissores->data_exame = date('d/m/Y');
		    		$focosTransmissores->inserido_por = \Yii::$app->user->identity->id;
		    		$focosTransmissores->atualizado_por = NULL;
		    		$focosTransmissores->especie_transmissor_id = NULL;
		    		$focosTransmissores->quantidade_ovos = NULL;
		    		$focosTransmissores->laboratorio = NULL;
		    		$focosTransmissores->tecnico = NULL;
		    		$focosTransmissores->imovel_id = NULL;
		    		$focosTransmissores->planilha_imovel_tipo_id = NULL;

		    		if($result = $focosTransmissores->save()) {

		    			$this->foco_transmissor_id = $model->id;
		    			$result = $this->save();
		    		} else {
die(var_dump($focosTransmissores->errors));
		    		}
		    	}

		    	if ($result) {
		        	$transaction->commit();
		        	return true;
		        }

		    }else {
die(var_dump($this->errors));
		    		}
		} catch (\Exception $e) {
		    $transaction->rollback();
		    throw $e;
		}
die(var_dump('hh'));
		$transaction->rollback();
		return false;
	}
}

