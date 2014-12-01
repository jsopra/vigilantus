<?php
namespace app\helpers\models;

use Yii;
use yii\helpers\StringHelper as YiiStringHelper;
use app\models\Denuncia;
use yii\helpers\Url;
use app\models\Municipio;
use app\models\DenunciaTipoImovel;

class DenunciaHelper extends YiiStringHelper
{
    /**
     * Retorna path de brasão
     * @param boolean $base Default is false
     * @return string
     */
    public static function getUploadPath()
    {
        return Yii::$app->params['dataDir'] . '/denuncias/';
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

    public static function getDetalhesDenuncia($model)
    {
        $html = '<ul>';

        $html .= '<li><strong>Bairro:</strong> ' . $model->bairro->nome . '</li>';

        if($model->endereco) {
            $html .= '<li><strong>Endereço:</strong> ' . $model->endereco . '</li>';
        }

        $html .= '<li><strong>Tipo do Imóvel:</strong> ' . DenunciaTipoImovel::getDescricao($model->tipo_imovel) . '</li>';

        if($model->pontos_referencia) {
            $html .= '<li><strong>Ptos. de referência:</strong> ' . $model->pontos_referencia . '</li>';
        }

        if($model->denuncia_tipo_problema_id) {
            $html .= '<li><strong>Tipo do Problema:</strong> ' . $model->denunciaTipoProblema->nome . '</li>';
        }

        $html .= '<li><strong>Mensagem:</strong> ' . $model->mensagem . '</li>';

        $html .= '</ul>';

        return $html;
    }
}
