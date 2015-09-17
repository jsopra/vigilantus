<?php
namespace app\helpers\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\StringHelper as YiiStringHelper;
use app\models\Ocorrencia;
use app\models\OcorrenciaStatus;
use yii\helpers\Url;
use app\models\Municipio;
use app\models\OcorrenciaTipoImovel;
use app\models\Configuracao;

class OcorrenciaHelper extends YiiStringHelper
{
    /**
     * Retorna path de brasão
     * @param boolean $base Default is false
     * @return string
     */
    public static function getUploadPath()
    {
        return Yii::$app->params['dataDir'] . '/ocorrencias/';
    }

    public static function getDadosContato($model)
    {
        $html = '<ul>';

        $html .= '<li><strong>Nome:</strong> ' . $model->nome . '</li>';

        if($model->email) {
            $html .= '<li><strong>Email:</strong> ' . $model->email . '</li>';
        }

        if($model->telefone) {
            $html .= '<li><strong>Telefone:</strong> ' . $model->telefone . '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    public static function getDetalhesOcorrencia($model)
    {
        $html = '<ul>';

        $html .= '<li><strong>Bairro:</strong> ' . $model->bairro->nome . '</li>';

        if($model->endereco) {
            $html .= '<li><strong>Endereço:</strong> ' . $model->endereco . '</li>';
        }

        $html .= '<li><strong>Tipo do Imóvel:</strong> ' . OcorrenciaTipoImovel::getDescricao($model->tipo_imovel) . '</li>';

        if($model->pontos_referencia) {
            $html .= '<li><strong>Ptos. de referência:</strong> ' . $model->pontos_referencia . '</li>';
        }

        if($model->ocorrencia_tipo_problema_id) {
            $html .= '<li><strong>Tipo do Problema:</strong> ' . $model->ocorrenciaTipoProblema->nome . '</li>';
        }

        $html .= '<li><strong>Mensagem:</strong> ' . $model->mensagem . '</li>';

        $html .= '</ul>';

        return $html;
    }

    public static function getTempoAberto($model)
    {
        if(in_array($model->status, OcorrenciaStatus::getStatusTerminativos())) {
            return null;
        }

        $color = null;
        $diasVerde = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_OCORRENCIA_VERDE, \Yii::$app->user->identity->cliente->id);
        $diasVemelho = Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_PINTAR_OCORRENCIA_VERMELHO, \Yii::$app->user->identity->cliente->id);

        $qtdeDias = $model->qtde_dias_em_aberto;
        if($qtdeDias <= $diasVerde) {
            $color = '4FD190';
        } else if($qtdeDias > $diasVerde && $qtdeDias <= $diasVemelho) {
            $color = 'FFFF50';
        } else if($qtdeDias > $diasVemelho) {
            $color = 'ff0000';
        }

        return '<span style="color:#' . $color . '; font-weight: bold;">' . $qtdeDias . ' dias</span>';
    }

    public static function getIcons()
    {
        $buttons = [];

        $buttons['detalhes'] = function ($model, $options = []) {
            return Html::a('<i class="glyphicon glyphicon-search"></i>', ['detalhes', 'id' => $model->id], [
                'title' => Yii::t('yii', 'Ver detalhes'),
            ] + $options);
        };

        $buttons['aprovar'] = function ($model, $options = []) {

            if(in_array($model->status, OcorrenciaStatus::getStatusTerminativos())) {
                return;
            }

            if($model->status != OcorrenciaStatus::AVALIACAO) {
                return;
            }

            return Html::a('<i class="glyphicon glyphicon-ok"></i>', ['aprovar', 'id' => $model->id], [
                'title' => Yii::t('yii', 'Aprovar'),
            ] + $options);
        };

        $buttons['reprovar'] = function ($model, $options = []) {

            if(in_array($model->status, OcorrenciaStatus::getStatusTerminativos())) {
                return;
            }

            if($model->status != OcorrenciaStatus::AVALIACAO) {
                return;
            }

            return Html::a('<i class="glyphicon glyphicon-remove"></i>', ['reprovar', 'id' => $model->id], [
                'title' => Yii::t('yii', 'Reprovar'),
            ] + $options);
        };

        $buttons['mudar-status'] = function ($model, $options = []) {

            if(in_array($model->status, OcorrenciaStatus::getStatusTerminativos())) {
                return;
            }

            if($model->status == OcorrenciaStatus::AVALIACAO) {
                return;
            }

            return Html::a('<i class="glyphicon glyphicon-transfer"></i>', ['mudar-status', 'id' => $model->id], [
                'title' => Yii::t('yii', 'Mudar status'),
                'data-method' => 'post',
            ] + $options);
        };

        $buttons['tentativa-averiguacao'] = function ($model, $options = []) {

            if(in_array($model->status, OcorrenciaStatus::getStatusTerminativos())) {
                return;
            }

            if($model->status == OcorrenciaStatus::AVALIACAO) {
                return;
            }

            return Html::a('<i class="glyphicon glyphicon-home"></i>', ['tentativa-averiguacao', 'id' => $model->id], [
                'title' => Yii::t('yii', 'Informar tentativa de averiguação'),
                'data-method' => 'post',
            ] + $options);
        };

        $buttons['anexo'] = function ($model, $options = []) {

            if(!$model->anexo) {
                return;
            }

            return Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['anexo', 'id' => $model->id], [
                'title' => Yii::t('yii', 'Baixar anexo'),
            ] + $options);
        };

        $buttons['comprovante'] = function ($model, $options = []) {
            return Html::a('<i class="glyphicon glyphicon-download-alt"></i>', ['comprovante', 'id' => $model->id], [
                'title' => Yii::t('yii', 'Baixar comprovante de ocorrência'),
            ] + $options);
        };

        return $buttons;
    }
}
