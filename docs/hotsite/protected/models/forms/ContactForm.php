<?php
class ContactForm extends CFormModel
{
    public $nome;
	public $instituicao;
	public $telefone;
	public $email;
	public $mensagem;
	
	public function rules() {
        return array(
            array('nome, instituicao, telefone, email, mensagem', 'required'),
			array('email', 'email'),
        );
    }
	
	public function attributeLabels() {
		return array(
			'nome' => 'Nome',
			'instituicao' => 'Instituição',
			'telefone' => 'Telefone',
			'email' => 'Email',
			'mensagem' => 'Mensagem',
		);
	}
	
	public function send() {
		$title = 'Resposta do mailing.perspectiva';
			
		$message = new YiiMailMessage;
		$message->view = 'template';
		$message->subject = $title;
		
		$view = Yii::app()->controller->renderFile(
			Yii::getPathOfAlias('application.views.email') . '/question.php', array(
				'message' => $this
			), true
		);
		$message->setBody(array('content' => $view, 'title' => $title), 'text/html', Yii::app()->charset);
		$message->from = Yii::app()->params['fromMail'];

		$message->addTo(Yii::app()->params['adminEmail']);

		return Yii::app()->mail->send($message);
	}
}