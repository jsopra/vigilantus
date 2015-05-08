<?php

namespace app\controllers;

use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use app\forms\LoginForm;
use app\forms\ContatoForm;
use app\forms\FeedbackForm;
use app\components\Controller;
use app\models\Municipio;
use app\models\report\ResumoRgCapaReport;
use app\models\report\ResumoFocosCapaReport;
use app\models\Modulo;
use yii\base\Exception;
use yii\base\UserException;
use app\models\Cliente;
use app\components\SocialLoginHandler;
use yii\helpers\Url;
use app\models\Denuncia;
use app\models\Configuracao;

class SiteController extends Controller
{
    public function init()
    {
        $rota = Yii::$app->requestedRoute;

        if($rota == '' || strstr($rota, 'site') !== null) {

            return parent::init();
        }

        return parent::init();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['feedback', 'logout', 'home', 'session', 'resumo-focos', 'resumo-denuncias'],
                'rules' => [
                    [
                        'actions' => ['feedback', 'logout', 'home', 'resumo-focos', 'resumo-denuncias'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['session'],
                        'allow' => true,
                        'roles' => ['Root'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successUrl' => Url::to(['/denuncia/social-account/index']),
                'successCallback' => [new SocialLoginHandler(), 'loginHandler'],
            ],
        ];
    }

    public function actionHome()
    {
        return $this->render(
            'home',
            [
                'modelRg' => new ResumoRgCapaReport,
                'cliente' => \Yii::$app->session->get('user.cliente'),
            ]
        );
    }

    public function actionResumoFocos()
    {
        return $this->render(
            'resumo-focos',
            [
                'modelFoco' => new ResumoFocosCapaReport,
                'cliente' => \Yii::$app->session->get('user.cliente'),
            ]
        );
    }

    public function actionResumoDenuncias()
    {
        $qtdeDiasVerde = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_DENUNCIA_VERDE, \Yii::$app->session->get('user.cliente')->id);
        $qtdeDiasVermelho = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_DENUNCIA_VERMELHO, \Yii::$app->session->get('user.cliente')->id);

        return $this->render(
            'resumo-denuncias',
            [
                'modelDenuncias' => new ResumoFocosCapaReport,
                'cliente' => \Yii::$app->session->get('user.cliente'),
                'diasVerde' => $qtdeDiasVerde,
                'diasVermelho' => $qtdeDiasVermelho,
                'qtdeVerde' => Denuncia::find()->aberta()->anteriorA($qtdeDiasVerde)->count(),
                'qtdeAmarelo' => Denuncia::find()->aberta()->entre($qtdeDiasVerde, $qtdeDiasVermelho)->count(),
                'qtdeVermelho' => Denuncia::find()->aberta()->posteriorA($qtdeDiasVermelho)->count(),
            ]
        );
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $this->redirect(['home']);
        }

        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->redirect(['home']);
        }

        $model = new LoginForm;

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

        if (!Yii::$app->user->isGuest) {
            $model->name = Yii::$app->user->identity->nome;
            $model->email = Yii::$app->user->identity->email;
        }

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
        $return = null;

        $model = new FeedbackForm;

        if ($model->load($_POST) && $model->validate())
            if ($model->sendFeedback(Yii::$app->user->identity, Yii::$app->params['emailFeedback']))
                $return = ['status' => true, 'message' => 'Feedback enviado com sucesso'];

        if($return === null)
            $return = ['status' => false, 'message' => 'Erro ao enviar mensagem'];

        header('Content-type: application/json; charset=UTF-8');

        echo Json::encode($return);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSession($id) {

        Yii::$app->session->set('user.cliente', Municipio::findOne($id)->cliente);

        Yii::$app->session->setFlash('success', 'Cliente alterado com sucesso');

        return $this->redirect(['home']);
    }

    public function actionError()
    {
        $municipio = str_replace('/', '', Yii::$app->getRequest()->getUrl());
        if($municipio) {

            $objeto = Cliente::find()->doRotulo($municipio)->one();
            if($objeto) {

                if($objeto->moduloIsHabilitado(Modulo::MODULO_DENUNCIA)) {
                    $this->redirect(['cidade/index', 'id' => $objeto->id]);
                }
            }
        }

        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            return '';
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        }
        else {
            $code = $exception->getCode();
        }

        if ($exception instanceof Exception) {
            $name = $exception->getName();
        }
        else {
            $name = $this->defaultName ?: Yii::t('yii', 'Error');
        }

        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        }
        else {
            $message = Yii::t('yii', 'An internal server error occurred.');
        }

        if (Yii::$app->getRequest()->getIsAjax()) {
            return "$name: $message";
        }
        else {
            return $this->render('error', [
                'name' => $name,
                'message' => $message,
                'exception' => $exception,
            ]);
        }
    }
}
