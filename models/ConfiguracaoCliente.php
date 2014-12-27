<?php

namespace app\models;
use app\components\ClienteActiveRecord;

/**
 * Este é a classe de modelo da tabela "configuracoes_clientes".
 *
 * Estas são as colunas disponíveis na tabela "configuracoes_clientes":
 * @property integer $id
 * @property integer $cliente_id
 * @property string $valor
 * @property integer $configuracao_id
 *
 * @property Clientes $cliente
 */
class ConfiguracaoCliente extends ClienteActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'configuracoes_clientes';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['cliente_id', 'valor', 'configuracao_id'], 'required'],
			[['cliente_id', 'configuracao_id'], 'integer'],
			[['valor'], 'string']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'cliente_id' => 'Cliente',
			'valor' => 'Valor',
			'configuracao_id' => 'Configuração',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCliente()
	{
		return $this->hasOne(Cliente::className(), ['id' => 'cliente_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getConfiguracao()
	{
		return $this->hasOne(Configuracao::className(), ['id' => 'configuracao_id']);
	}

    public function afterValidate()
    {
		switch($this->configuracao->tipo) {

			case ConfiguracaoTipo::TIPO_INTEIRO : {

				if(!ctype_digit($this->valor)) {
					$this->addError('valor', 'Valor inválido para tipo inteiro');
				}
				break;
			}

			case ConfiguracaoTipo::TIPO_DECIMAL : {

				$this->valor = str_replace(',', '.', $this->valor);
	            if(!is_numeric($this->valor)) {
	                $this->addError('valor', 'Valor inválido para tipo decimal');
	            }
				break;
			}

			case ConfiguracaoTipo::TIPO_BOLEANO : {

				$this->valor = $this->valor == '0' ? 'false' : 'true';
				break;
			}

			case ConfiguracaoTipo::TIPO_RANGE : {

                $valoresPossiveis = unserialize($this->configuracao->valores_possiveis);
                if(!in_array($this->valor, array_keys($valoresPossiveis))) {
                    $this->addError('valor', 'Valor fora do padrão especificado: {x}', array('{x}' => array_keys($valoresPossiveis)));
                }
				break;
			}

			case ConfiguracaoTipo::TIPO_TIME : {

				if(!preg_match("/^((([01][0-9])|([2][0-3])):([0-5][0-9]))|(24:00)$/", $this->valor)) {
                	$this->addError('valor', 'Não é um horário válido');
				}
				break;
			}
		}

        return parent::afterValidate();
    }

    public function afterFind()
    {
    	switch($this->configuracao->tipo) {

			case ConfiguracaoTipo::TIPO_DECIMAL : {

				$this->valor = str_replace('.', ',', $this->valor);
				break;
			}

			case ConfiguracaoTipo::TIPO_BOLEANO : {

				$this->valor = $this->valor == 'false' ? '0' : '1';
				break;
			}
		}

        return parent::afterFind();
    }
}
