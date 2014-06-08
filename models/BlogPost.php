<?php

namespace app\models;
use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "blog_post".
 *
 * Estas são as colunas disponíveis na tabela "blog_post":
 * @property integer $id
 * @property string $data
 * @property string $titulo
 * @property string $descricao
 * @property string $texto
 */
class BlogPost extends ActiveRecord 
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'blog_post';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
        // AVISO: só defina regras dos atributos que receberão dados do usuário
		return [
			[['data', 'titulo', 'descricao', 'texto'], 'string'],
			[['titulo', 'texto'], 'required']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'data' => 'Data',
			'titulo' => 'Título',
			'descricao' => 'Descrição',
			'texto' => 'Texto',
		];
	}
}
