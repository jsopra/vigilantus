<?php

namespace app\controllers;

use app\components\CRUDController;
use Yii;
use yii\filters\AccessControl;

class EquipeController extends CRUDController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'index'],
                        'roles' => ['Gerente', 'Supervisor'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModelClass = $this->getSearchModelClassName();
        $searchModel = new $searchModelClass;
        $searchModel->usuario = Yii::$app->user;
        $dataProvider = $searchModel->search($_GET);

        return $this->renderAjaxOrLayout(
            'index',
            ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]
        );
    }
}
