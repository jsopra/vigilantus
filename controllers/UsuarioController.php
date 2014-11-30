<?php

namespace app\controllers;

use app\components\CRUDController;
use app\forms\AlterarSenhaForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class UsuarioController extends CRUDController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['change-password','create', 'delete', 'index', 'update'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['change-password'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'index'],
                        'roles' => ['Administrador'],
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
    
    public function actionChangePassword()
    {
        $model = new AlterarSenhaForm;
        
        if ($model->load($_POST) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Senha alterada com sucesso.');
            return $this->redirect(['site/home']);
        }
        
        return $this->render('change-password', ['model' => $model]);
    }
    
    /**
     * @inheritdoc
     */
    protected function buildNewModel()
    {
        $model = parent::buildNewModel();
        $model->cliente_id = Yii::$app->user->identity->cliente_id;
        return $model;
    }
}
