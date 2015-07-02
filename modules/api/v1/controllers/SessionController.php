<?php
namespace app\modules\api\v1\controllers;

use app\modules\api\models\Session;
use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

class SessionController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        unset($behaviors['authenticator']);
        return $behaviors;
    }

    public function actionCreate()
    {
        $model = new Session;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->save()) {
            Yii::$app->getResponse()->setStatusCode(201);
        }

        return $model;
    }

    public function actionOptions()
    {
        if (Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
            Yii::$app->getResponse()->setStatusCode(405);
        }
        $options = ['POST', 'OPTIONS'];
        Yii::$app->getResponse()->getHeaders()->set('Allow', implode(', ', $options));
    }
}
