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
 * @property string $inicio
 * @property string $fim
 */
class SemanaEpidemiologica extends ClienteActiveRecord
{
    public $bairro_id;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'semanas_epidemiologicas';
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
			[['cliente_id', 'inserido_por', 'nome', 'inicio', 'fim'], 'required'],
			[['cliente_id', 'inserido_por', 'atualizado_por', 'bairro_id'], 'integer'],
            [['inicio', 'fim'], 'date'],
            ['inicio', 'compare', 'compareAttribute' => 'fim', 'operator' => '<', 'enableClientValidation' => false],
			[['data_cadastro', 'data_atualizacao', 'bairro_id'], 'safe']
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
			'inicio' => 'Início',
			'fim' => 'Fim',
			'bairro_id' => 'Bairro',
		];
	}
}
