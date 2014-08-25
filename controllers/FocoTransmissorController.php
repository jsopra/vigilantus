<?php

namespace app\controllers;

use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\CRUDController;
use app\batch\controller\Batchable;

class FocoTransmissorController extends CRUDController
{
    public function actions()
    {
        return [
            'bairroCategoria' => ['class' => 'app\components\actions\BairroCategoria'],
            'bairroQuarteiroes' => ['class' => 'app\components\actions\BairroQuarteiroes'],
            'ruas' => ['class' => 'app\components\actions\Ruas'],
            'imoveis' => ['class' => 'app\components\actions\Imoveis'],
            'batch' => [
                'class' => 'app\\batch\\Action',
                'modelClass' => 'app\\models\\batch\\FocosTransmissor',
            ]
        ];
    }
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'delete', 'index', 'update', 'imoveis', 'batch'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'index', 'imoveis', 'bairroCategoria', 'bairroQuarteiroes', 'ruas', 'batch'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!empty($_POST)) {

            $model = is_object($id) ? $id : $this->findModel($id);

            if (!$this->loadAndSaveModel($model, $_POST)) {
                return $this->render('update', ['model' => $model]);
            }

        } else {
            $model->popularBairro();
        }

        return $this->render('update', ['model' => $model]);
    }
}
