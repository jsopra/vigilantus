<?php
namespace app\jobs;

use app\models\Ocorrencia;
use app\models\OcorrenciaStatus;
use perspectivain\gearman\InterfaceJob;
use Yii;
use yii\helpers\Url;
use yii\swiftmailer\Message;

class AlertaAlteracaoStatusOcorrenciaJob implements InterfaceJob
{
    public function run($params = [])
    {
        if (!isset($params['key']) || $params['key'] != getenv('GEARMAN_JOB_KEY')) {
            return true;
        }

        if (!isset($params['id'])) {
            return true;
        }

        $isNewRecord = !empty($params['isNewRecord']);

        $model = Ocorrencia::find()->andWhere(['id' => $params['id']])->one();
        if (!$model) {
            return true;
        }

        $message = Yii::$app->mail->compose()
            ->setTo($model->email)
            ->setFrom([Yii::$app->params['emailContato'] => 'Vigilantus'])
        ;

        if ($isNewRecord) {
            $this->criarMensagemNovaOcorrencia($message, $model);
        } else {
            $this->criarMensagemStatusAlterado($message, $model);
        }

        $message->send();

        return true;
    }

    protected function criarMensagemNovaOcorrencia(Message $message, Ocorrencia $model)
    {
        $body = '<h1>Ocorrência registrada com sucesso</h1>';
        $body .= '<p>Olá' . ($model->nome ? ', ' . $model->nome : '') . ',</p>';
        $body .= '<p>Sua ocorrência foi registrada com sucesso. Agradecemos pela sua contribuição para melhorar a nossa cidade!</p>';
        $body .= '<p>Você será informado quando houver alguma atualização sobre'
               . ' o andamento da avaliação da sua ocorrência, ou se preferir, '
               . 'você poderá acompanhar diretamente na <a href="'
               . Url::to(
                    [
                        '/ocorrencia/cidade/acompanhar-ocorrencia',
                        'slug' => $model->cliente->municipio->slug,
                        'hash' => $model->hash_acesso_publico,
                    ],
                    true
                ) . '">página da Prefeitura</a> através do protocolo.</p>'
        ;
        $body .= '<p><big><big>Protocolo: ' . $model->hash_acesso_publico . '</big></big></p>';

        $message->setSubject('Ocorrência registrada com sucesso');
        $message->setHtmlBody($body);
    }

    protected function criarMensagemStatusAlterado(Message $message, Ocorrencia $model)
    {
        $body = '<h1>Alteração de status da ocorrência #' . $model->hash_acesso_publico . '</h1>';
        $body .= '<p>Olá' . ($model->nome ? ', ' . $model->nome : '') . ',</p>';
        $body .= '<p>Informamos que sua ocorrência teve o status alterado para: '
               . '<strong>' . OcorrenciaStatus::getDescricao($model->status)
               . '</strong>.</p>'
        ;
        if (OcorrenciaStatus::isStatusTerminativo($model->status)){
            $body .= '<p><a href="' . Url::to(
             [
                 '/ocorrencia/cidade/avaliar-ocorrencia',
                 'slug' => $model->cliente->municipio->slug,
                 'hash' => $model->hash_acesso_publico,
             ],
             true
         ) . '">Avalie aqui o atendimento desta ocorrência</a></p>';
        }

        if ($model->detalhes_publicos != ''){
            $body .= '<p><strong>Observações: </strong>' . $model->detalhes_publicos . '</p>';
        }

        $body .= '<hr />';
        $body .= '<p><a href="' . Url::to(
             [
                 '/ocorrencia/cidade/acompanhar-ocorrencia',
                 'slug' => $model->cliente->municipio->slug,
                 'hash' => $model->hash_acesso_publico,
             ],
             true
         ) . '">Acompanhe aqui a sua ocorrência</a></p>';

        $message->setSubject('Alteração de status da ocorrência #' . $model->hash_acesso_publico);
        $message->setHtmlBody($body);
    }
}
