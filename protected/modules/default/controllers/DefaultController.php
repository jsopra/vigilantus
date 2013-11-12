<?php

class DefaultController extends Controller
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
				'actions'=>array('index','error'),
				'users'=>array('@'),
			),			      
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndex()
	{        
		if(Yii::app()->user->isGuest) {
			$this->redirect(array("default/session/login")); 
		}
		
		$this->render('index');
	}
	
	/**
     * This is the action to handle external exceptions.
     * 
     * @todo o erro vai ter que ser personalizado (não tem tarefa, pois vai aguardar contexto da aplicação)
     * 
     * @return void
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
}