<?php

/**
 * Controlador de sessões
 */
class SessionController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index'),
				'users'=>array('@'),
			),			      
			array('allow',
				'actions'=>array('login','logout'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
	
    /**
     * Displays the login page
     * 
     * @todo ver se start é o melhor nome para a action de registrar-se e logar-se
     * @todo talvez essa página ainda caia fora, fazendo as funcionalidades desta irem para outras páginas, 
     * mas as funcionalidades serão as aqui dispostas
     * 
     * @return void
     */
    public function actionLogin() {

		if(!Yii::app()->user->isGuest) {
			$this->redirect(array("default/index")); 
		}
		
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // login através do formulário
        // collect user input data
        if (isset($_POST['LoginForm'])) {

            $user = new User();

			
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $user->internalLogin($_POST['LoginForm']['username'], $_POST['LoginForm']['password']))
                $this->redirect(Yii::app()->user->returnUrl);
        }
		
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     * 
     * @return void
     */
    public function actionLogout()
    {
        User::logout();

        $this->redirect(Yii::app()->homeUrl . '/admin');
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) /* && $_POST['ajax'] === 'additional-data-form' */) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}