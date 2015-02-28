<?php
namespace app\jobs;

use Yii;
use yii\helpers\Url;
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
        $message .= '<hr />';
        $message .= '<a href="' . getenv('VIGILANTUS_BASE_PATH') . 'cidade/acompanhar-denuncia?id=' . $model->cliente->id . '&hash=' . $model->hash_acesso_publico . '">Acompanhe aqui a sua denúncia</a>';

        Yii::$app->mail->compose()
            ->setTo($model->email)
            ->setFrom([Yii::$app->params['emailContato'] => 'Vigilantus'])
            ->setSubject('Alteração de status de Denúncia')
            ->setHtmlBody($message)
            ->send();
    }
}
