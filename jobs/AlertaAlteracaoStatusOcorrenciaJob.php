<?php
namespace app\jobs;

use Yii;
use yii\helpers\Url;
use app\models\Ocorrencia;
use app\models\OcorrenciaStatus;

class AlertaAlteracaoStatusOcorrenciaJob implements \perspectivain\gearman\InterfaceJob
{
    public function run($params = [])
    {
        if(!isset($params['key']) || $params['key'] != getenv('GEARMAN_JOB_KEY')) {
            return true;
        }

        if(!isset($params['id'])) {
            return true;
        }

        $model = Ocorrencia::find()->andWhere(['id' => $params['id']])->one();
        if(!$model) {
            return true;
        }

        $message = '<p><strong>Alteração de status em ocorrência</strong></p>';
        $message .= '<p>Olá, ' . ($model->nome ? $model->nome : '') . ',</p>';
        $message .= '<p>Informamos que sua ocorrência teve o status alterado para: <strong>' . OcorrenciaStatus::getDescricao($model->status) . '</strong>.</p>';
        $message .= '<hr />';
        $message .= '<a href="' . getenv('VIGILANTUS_BASE_PATH') . 'cidade/acompanhar-ocorrencia?id=' . $model->cliente->id . '&hash=' . $model->hash_acesso_publico . '">Acompanhe aqui a sua ocorrência</a>';

        Yii::$app->mail->compose()
            ->setTo($model->email)
            ->setFrom([Yii::$app->params['emailContato'] => 'Vigilantus'])
            ->setSubject('Alteração de status de Ocorrência')
            ->setHtmlBody($message)
            ->send();

        return true;
    }
}
