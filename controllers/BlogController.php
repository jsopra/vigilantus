<?php

namespace app\controllers;

use Yii;
use app\components\Controller;
use app\models\BlogPost;
use yii\data\Pagination;

class BlogController extends Controller
{
    public function actionIndex() 
    {
        $query = BlogPost::find();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->pageSize = 5;
        $models = $query
            ->orderBy('data DESC')
            ->offset($pages->offset)
            ->limit($pages->limit)    
            ->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }
}
