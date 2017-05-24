<?php
namespace app\jobs;

use Yii;
use yii\helpers\Url;
use app\models\Ocorrencia;
use app\models\OcorrenciaHistorico;

class AlertaVisitacaoOcorrenciaJob implements \perspectivain\gearman\InterfaceJob
{
    public function run($params = [])
    {
        if (!isset($params['key']) || $params['key'] != getenv('GEARMAN_JOB_KEY')) {
            return true;
        }

        if (!isset($params['id']) || !isset($params['historico_id'])) {
            return true;
        }

        $model = Ocorrencia::find()->andWhere(['id' => $params['id']])->one();
        if (!$model) {
            return true;
        }

        $historico = OcorrenciaHistorico::find()->andWhere(['id' => $params['historico_id']])->one();
        if (!$historico) {
            return true;
        }

        $message = '<p><strong>Alerta de visitação em ocorrência</strong></p>';
        $message .= '<p>Olá' . ($model->nome ? ', ' . $model->nome : '') . ',</p>';
        $message .= '<p>Houve um visitação ao local de sua ocorrência em ' . $historico->getFormattedAttribute('data_associada') . '.</p>';

        $message .= '<hr />';
        $message .= '<a href="' . getenv('ABSOLUTE_URL') . $model->cliente->municipio->slug . '/ocorrencias/' . $model->hash_acesso_publico . '">Acompanhe aqui a sua ocorrência</a>';

        Yii::$app->mail->compose()
            ->setTo($model->email)
            ->setFrom([Yii::$app->params['emailContato'] => 'Vigilantus'])
            ->setSubject('Alerta de visitação de Ocorrência')
            ->setHtmlBody($message)
            ->send();

        return true;
    }
}
