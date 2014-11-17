<?php 
namespace app\jobs;

use Yii;
use app\models\Denuncia;
use app\models\DenunciaStatus;

class AlertaAlteracaoStatusDenunciaJob implements AbstractJob
{
    public function run($params = []) 
    { 
        if(!isset($params['id'])) {
            return;
        }

        $model = Denuncia::find()->andWhere(['id' => $params['id']])->one();

        if(!$model) {
            return;
        }

        $message = '<p><strong>Alteração de status em denúncia</strong></p>';
        $message .= '<p>Olá, ' . ($model->nome ? $model->nome : '') . ',</p>';
        $message .= '<p>Informamos que sua denúncia teve o status alterado para: <strong>' . DenunciaStatus::getDescricao($model->status) . '</strong>.</p>';

        Yii::$app->mail->compose()
            ->setTo($model->email)
            ->setFrom([Yii::$app->params['emailContato'] => 'Vigilantus'])
            ->setSubject('Alteração de status de Denúncia')
            ->setHtmlBody($message)
            ->send();
    }
}