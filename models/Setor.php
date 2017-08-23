<?php

namespace app\models;
use app\components\ClienteActiveRecord;

/**
 * Este é a classe de modelo da tabela "setores".
 *
 * Estas são as colunas disponíveis na tabela "setores":
 * @property integer $id
 * @property string $nome
 * @property integer $cliente_id
 * @property integer $inserido_por
 * @property string $data_cadastro
 * @property integer $atualizado_por
 * @property string $data_atualizacao
 * @property string $padrao_ocorrencias
 */
class Setor extends ClienteActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'setores';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['nome'], 'string'],
			['padrao_ocorrencias', 'boolean'],
			['nome', 'unique', 'compositeWith' => 'cliente_id'],
			[['cliente_id', 'inserido_por', 'nome'], 'required'],
			[['cliente_id', 'inserido_por', 'atualizado_por'], 'integer'],
            [['padrao_ocorrencias'], 'validatePadrao'],
			[['data_cadastro', 'data_atualizacao'], 'safe']
		];
	}

    public function validatePadrao()
    {
    	if ($this->padrao_ocorrencias == false) {
    		return true;
    	}
    		
        $padraoParaMunicipio = self::find()
            ->padraoParaOcorrencias()
            ->queNao($this->id)
            ->count();

        if($padraoParaMunicipio > 0) {
            $this->addError('padrao_ocorrencias', 'Já existe um setor padrão para ocorrências no município');
            return false;
        }

        return true;
    }

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'nome' => 'Nome',
			'cliente_id' => 'Cliente',
			'inserido_por' => 'Inserido por',
			'data_cadastro' => 'Data do cadastro',
			'atualizado_por' => 'Atualizado por',
			'data_atualizacao' => 'Data da atualização',
			'padrao_ocorrencias' => 'Padrão de Novas Ocorrências'
		];
	}

	public function getQuantidadeUsuarios()
    {
        return SetorUsuario::find()->where(['setor_id' => $this->id])->count();
    }

    public function beforeDelete()
    {
        $parent = parent::beforeDelete();

        $this->_clearRelationships();

        return $parent;
    }

    /**
     * Apaga relações do boletim com imóveis e fechamento de RG
     * @return void
     */
    private function _clearRelationships()
    {
        foreach (SetorUsuario::find()->where('setor_id = :setor', [':setor' => $this->id])->all() as $registro) {
            $registro->delete();
        }
    }
}
