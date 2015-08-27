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
			['nome', 'unique', 'compositeWith' => 'cliente_id'],
			[['cliente_id', 'inserido_por'], 'required'],
			[['cliente_id', 'inserido_por', 'atualizado_por'], 'integer'],
			[['data_cadastro', 'data_atualizacao'], 'safe']
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
			'cliente_id' => 'Cliente',
			'inserido_por' => 'Inserido por',
			'data_cadastro' => 'Data do cadastro',
			'atualizado_por' => 'Atualizado por',
			'data_atualizacao' => 'Data da atualização',
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
