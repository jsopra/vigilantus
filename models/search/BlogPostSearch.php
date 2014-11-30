<?php

namespace app\models\search;

use app\components\SearchModel;

/**
 * BlogPostSeach represents the model behind the search form about BlogPost.
 */
class BlogPostSearch extends SearchModel
{
	public $id;
	public $data;
	public $titulo;
	public $descricao;

	public function rules()
	{
		return [
			[['id'], 'integer'],
			[['data', 'titulo', 'descricao'], 'safe'],
		];
	}

	public function searchConditions($query)
	{
        $this->addCondition($query, 'id');
		$this->addCondition($query, 'descricao');
        $this->addCondition($query, 'titulo');
        $this->addCondition($query, 'data');
        
	}
}
