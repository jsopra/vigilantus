<?php
class DefaultController extends Controller
{
	public function actionIndex()
	{
		if(isset($_POST['ContactForm'])) {

			$this->contactForm->attributes=$_POST['ContactForm'];
			if($this->contactForm->validate() && $this->contactForm->send()) {

				Yii::app()->user->setFlash('success', 'Mensagem enviada com sucesso! Em breve entraremos em contato com você.');
				$this->redirect(Yii::app()->getBaseUrl(true) . '#start');
				
			}
		}
		
		$this->render('index', array());
	}
	
    public function actionError() {
		
        if ($error = Yii::app()->errorHandler->error) 
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        
    }
}
