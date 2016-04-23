<?php

namespace app\models\mail;

use app\models\Cliente;
use app\models\Usuario;
use app\models\UsuarioRole;
use app\models\indicador\OcorrenciasResumoReport;
use yii\swiftmailer\Message;
use Yii;

class OcorrenciasMail
{
    public function send()
    {
        foreach (Cliente::find()->ativo()->all() as $cliente) {

            $to = [];

            $usuarios = Usuario::find()->ativo()->doCliente($cliente)->recebeEmailOcorrencias()->all();
            foreach ($usuarios as $usuario) {
                $to[] = $usuario->email;
            }

            if (count($to) == 0) {
                continue;
            }

            $modelResumo = new OcorrenciasResumoReport;
            $modelResumo->ano = date('Y');

            Yii::$app->mail
                ->compose('ocorrencias', ['cliente' => $cliente, 'resumo' => $modelResumo])
                ->setTo($to)
                ->setFrom([Yii::$app->params['emailContato'] => 'Vigilantus'])
                ->setSubject('Resumo de Ocorrências')
                ->send();
        }
    }
}
