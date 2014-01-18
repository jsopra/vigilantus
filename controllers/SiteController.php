<?php

namespace app\controllers;

use Yii;
use yii\db\Expression;
use yii\web\AccessControl;
use yii\web\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use app\forms\LoginForm;
use app\forms\ContatoForm;
use app\forms\FeedbackForm;
use app\components\Controller;

class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['feedback', 'logout'],
                'rules' => [
                    [
                        'actions' => ['feedback', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'feedback' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load($_POST) && $model->login()) {
            
            $model->user->ultimo_login = new Expression('NOW()');
            $model->user->update(false, ['ultimo_login']);
            
            return $this->goBack();
        } else {
            return $this->render('login', [
                    'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionContato()
    {
        $model = new ContatoForm;
        if ($model->load($_POST) && $model->contact(Yii::$app->params['emailContato'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        } else {
            return $this->render('contact', [
                    'model' => $model,
            ]);
        }
    }
    
    public function actionFeedback() 
    {
        $return = array();
        
        $model = new FeedbackForm;
        if ($model->load($_POST) && $model->sendFeedback(Yii::$app->user->identity, Yii::$app->params['emailContato'])) 
            $return = [
                'status' => true,
                'message' => 'Feedback enviado com sucesso',
            
            ];
        else
            $return = [
                'status' => false,
                'message' => 'Erro ao enviar mensagem',
            
            ];
        
        header('Content-type: application/json; charset=UTF-8');
        echo Json::encode($return);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
