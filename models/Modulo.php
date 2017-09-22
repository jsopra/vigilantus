<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "modulos".
 *
 * Estas são as colunas disponíveis na tabela "modulos":
 * @property integer $id
 * @property string $nome
 * @property boolean $ativo
 * @property string $data_cadastro
 * @property string $data_atualizacao
 */
class Modulo extends ActiveRecord
{
	const MODULO_OCORRENCIA = 1;
	const MODULO_VISITACAO = 2;
	const MODULO_LOCALIZACAO = 3;
	const MODULO_FOCOS = 4;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'modulos';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['nome'], 'required'],
			[['nome'], 'string'],
			[['ativo'], 'boolean'],
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
			'ativo' => 'Ativo',
			'data_cadastro' => 'Data de Cadastro',
			'data_atualizacao' => 'Data de Atualização',
		];
	}
}
