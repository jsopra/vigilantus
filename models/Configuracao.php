<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "configuracoes".
 *
 * Estas são as colunas disponíveis na tabela "configuracoes":
 * @property integer $id
 * @property string $nome
 * @property string $descricao
 * @property string $tipo
 * @property string $valor
 * @property string $valores_possiveis
 */
class Configuracao extends ActiveRecord
{
    const ID_QUANTIDADE_DIAS_INFORMACAO_PUBLICA = 1;
    const ID_QUANTIDADE_DIAS_PINTAR_DENUNCIA_VERDE = 2;
    const ID_QUANTIDADE_DIAS_PINTAR_DENUNCIA_VERMELHO = 3;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'configuracoes';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['nome', 'descricao', 'tipo', 'valor'], 'required'],
            ['tipo', 'in', 'range' => ConfiguracaoTipo::getTiposDeConfiguracao()],
			[['nome', 'descricao', 'tipo', 'valor', 'valores_possiveis'], 'string']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'nome' => 'Nome',
			'descricao' => 'Descrição',
			'tipo' => 'Tipo',
			'valor' => 'Valor',
			'valores_possiveis' => 'Valores Possiveis',
		];
	}

    public function afterValidate()
    {
        switch($this->tipo) {

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

                if(!$this->valores_possiveis) {
                    $this->addError('valor', 'Tipo range deve especificar valores possíveis');
                }
                else {

                    $valoresPossiveis = unserialize($this->valores_possiveis);
                    if(!in_array($this->valor, array_keys($valoresPossiveis))) {
                        $this->addError('valor', 'Valor fora do padrão especificado: {x}', array('{x}' => array_keys($valoresPossiveis)));
                    }
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

    public function getValor($idCliente = null)
    {
    	$configuracao = $this;
    	$tipo = $this->tipo;
        $valor = $this->valor;

        if($idCliente) {
        	$configuracaoCliente = ConfiguracaoCliente::find()->doCliente($idCliente)->doIdConfiguracao($this->id)->one();
        	if($configuracaoCliente) {
                $valor = $configuracaoCliente->valor;
        	}
    		unset($configuracaoCliente);
        }

		switch($tipo) {

			case ConfiguracaoTipo::TIPO_BOLEANO : {
				return $valor == '1';
			}
			case ConfiguracaoTipo::TIPO_RANGE : {
				$valores = unserialize($configuracao->valores_possiveis);
            	return $valor && $valores[$valor] ? $valores[$valor] : $valor;
			}
			default : {
				return $valor;
			}
		}
    }

    public function getDescricaoValor($idCliente = null)
    {
    	$configuracao = $this;
    	$tipo = $this->tipo;
        $valor = $this->valor;

        if($idCliente) {
        	$configuracaoCliente = ConfiguracaoCliente::find()->doCliente($idCliente)->doIdConfiguracao($this->id)->one();
        	if($configuracaoCliente) {
        		$tipo = $configuracaoCliente->configuracao->tipo;
                $valor = $configuracaoCliente->valor;
        	}
    		unset($configuracaoCliente);
        }

    	switch($tipo) {

			case ConfiguracaoTipo::TIPO_BOLEANO : {
				return $valor == '1' ? 'Sim' : 'Não';
			}
			case ConfiguracaoTipo::TIPO_RANGE : {
				$valores = unserialize($configuracao->valores_possiveis);
            	return $valor && $valores[$valor] ? $valores[$valor] : $valor;
			}
			default : {
				return $valor;
			}
		}
    }

    /**
     * Cria configuração para todas as empresas
     * @param int $id
     * @param string $nome
     * @param string $descricao
     * @param string $tipo
     * @param string $valor
     * @param array $values
     * @return void
     */
    public static function cria($id, $nome, $descricao, $tipo, $valor, $values = null)
    {
        \Yii::$app->db->createCommand()->execute("ALTER SEQUENCE configuracoes_id_seq RESTART WITH " . $id);

    	$configuracao = new Configuracao;
        $configuracao->nome = $nome;
        $configuracao->descricao = $descricao;
        $configuracao->tipo = $tipo;
        $configuracao->valor = $valor;
        $configuracao->valores_possiveis = $tipo == ConfiguracaoTipo::TIPO_RANGE ? serialize($values) : null;

        if(!$configuracao->save()) {
        	return false;
        }

        $clientes = Cliente::find()->all();
        foreach($clientes as $cliente) {

            $conf = new ConfiguracaoCliente;
            $conf->cliente_id = $cliente->id;
            $conf->configuracao_id = $configuracao->id;
            $conf->valor = $configuracao->valor;
            $conf->save();
        }

        return true;
    }

    public static function getValorConfiguracaoParaCliente($idConfiguracao, $idCliente)
    {
        $configuracao = self::find()->doId($idConfiguracao)->one();

        if(!$configuracao) {
            return null;
        }

        return $configuracao->getValor($idCliente);
    }

    public function beforeDelete()
    {
        $parent = parent::beforeDelete();
        $this->_clearRelationships();
        return $parent;
    }

    /**
     * Apaga relações da configuração em clientes
     * @return void
     */
    private function _clearRelationships()
    {
        foreach (BoletimRgFechamento::find()->where('configuracao_id = :configuracao', [':configuracao' => $this->id])->all() as $configuracao) {
            $configuracao->delete();
        }
    }
}
