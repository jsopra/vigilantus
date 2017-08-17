<?php
namespace app\jobs;

use app\models\Ocorrencia;
use perspectivain\gearman\InterfaceJob;
use Yii;
use yii\helpers\Url;
use yii\swiftmailer\Message;

class AlertaAlteracaoSetorOcorrenciaJob implements InterfaceJob
{
    public function run($params = [])
    {
        if (!isset($params['key']) || $params['key'] != getenv('GEARMAN_JOB_KEY')) {
            return true;
        }

        if (!isset($params['id'])) {
            return true;
        }

        $model = Ocorrencia::find()->andWhere(['id' => $params['id']])->one();
        if (!$model) {
            return true;
        }

        $message = Yii::$app->mail->compose()
            ->setTo($model->email)
            ->setFrom([Yii::$app->params['emailContato'] => 'Vigilantus'])
        ;

        $this->criarMensagem($message, $model);

        $message->send();

        return true;
    }

    protected function criarMensagem(Message $message, Ocorrencia $model)
    {
        $body = '<h1>Alteração de setor da ocorrência #' . $model->hash_acesso_publico . '</h1>';
        $body .= '<p>Olá' . ($model->nome ? ', ' . $model->nome : '') . ',</p>';
        $body .= '<p>Informamos que sua ocorrência teve o setor alterado para: '
               . '<strong>' . $model->setor->nome
               . '</strong>.</p>'
        ;

        $body .= '<hr />';
        $body .= '<p><a href="' .  getenv('ABSOLUTE_URL') . $model->cliente->municipio->slug . '/ocorrencias/' . $model->hash_acesso_publico . '">Acompanhe aqui a sua ocorrência</a></p>';

        $message->setSubject('Alteração de setor da ocorrência #' . $model->hash_acesso_publico);
        $message->setHtmlBody($body);
    }
}
