<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\Usuario;

class FeedbackForm extends Model
{
    public $body;
    public $url;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['body','url'], 'required', 'message'=>'Você deve escrever alguma coisa'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'body' => 'Envie comentários, ideias, problemas, ...',
        ];
    }


    public function sendFeedback(Usuario $user, $email)
    {
        $municipio = $user->getCliente()->municipio;
        
        $message = '<p>Usuário: ' . $user->nome . ' (' . $user->login . ')</p>';
        if($user->email) {
            $message .= '<p>E-mail do usuário: ' . $user->email . '</p>';
        }
        $message .= '<p>Município: ' . $municipio->nome . '/' . $municipio->sigla_estado . '</p>';
        $message .= '<p>Data/Hora: '. date('d/m/Y H:i:s') . '</p>';
        $message .= '<p>URL: ' . $this->url . '</p>';
        $message .= '<p>Mensagem: ' . $this->body . '</p>';

        if ($this->validate()) {
            Yii::$app->mail->compose()
                ->setTo($email)
                ->setFrom([$user->email => $user->nome])
                ->setSubject('Feedback do Vigilantus')
                ->setHtmlBody($message)
                ->send();
            return true;
        } else {
            return false;
        }
    }
}
