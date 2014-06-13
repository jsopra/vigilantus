<?php

namespace app\models;
use yii\db\Expression;
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
     * @param array $config
     */
    public function __construct($config = [])
    {
        $behaviors = [];

        $behaviors['TimestampBehavior'] = [
            'class' => 'yii\behaviors\TimestampBehavior',
            'value' => new Expression('NOW()'),
            'attributes' => [],
        ];

        $behaviors['TimestampBehavior']['attributes'][ActiveRecord::EVENT_BEFORE_INSERT] = 'data';

        $this->attachBehaviors($behaviors);

        parent::__construct($config);
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
