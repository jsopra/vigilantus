<?php
namespace app\ocorrencia\controllers;

use Yii;
use app\components\Controller;
use app\helpers\models\OcorrenciaHelper;
use app\models\Cliente;
use app\models\Configuracao;
use app\models\Estado;
use app\models\Municipio;
use app\models\Ocorrencia;
use app\models\OcorrenciaHistorico;
use app\models\OcorrenciaStatus;
use app\models\Modulo;
use app\models\FocoTransmissor;
use app\models\UsuarioRole;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\HttpException;
use yii\web\UploadedFile;

class CidadeController extends Controller
{
    public function actionIndex($uf)
    {
        $estado = Estado::findOne(['uf' => strtoupper($uf)]);

        if (empty($estado)) {
            throw new HttpException(404, 'Estado não encontrado.');
        }

        return $this->render(
            'index',
            [
                'estado' => $estado,
                'query' => Municipio::find()
                    ->where(['sigla_estado' => strtoupper($uf)])
                    ->orderBy('slug')
            ]
        );
    }

    public function actionView($slug)
    {
        $municipio = $this->module->municipio;
        $cliente = Cliente::find()->doMunicipio($municipio->id)->one();
        $dataPrimeiraOcorrencia = null;
        $setor = null;
        $percentualOcorrencias = 0;

        if ($cliente) {
            $dataPrimeiraOcorrencia = $cliente->data_cadastro;
        }

        $numeroOcorrenciasRecebidas = $municipio->getOcorrencias()->count();
        $numeroOcorrenciasAtendidas = $municipio->getOcorrencias()->fechada()->count();
        $primeiraOcorrencia = $municipio->getOcorrencias()->orderBy('data_criacao ASC')->limit(1)->one();

        if ($numeroOcorrenciasRecebidas) {
            $percentualOcorrencias = $numeroOcorrenciasAtendidas / $numeroOcorrenciasRecebidas * 100;
        }
        if ($primeiraOcorrencia) {
            $dataPrimeiraOcorrencia = $primeiraOcorrencia->data_criacao;
        }

        return $this->render(
            'view',
            [
                'municipio' => $municipio,
                'numeroOcorrenciasRecebidas' => $numeroOcorrenciasRecebidas,
                'percentualOcorrenciasAtendidas' => round($percentualOcorrencias),
                'dataPrimeiraOcorrencia' => Yii::$app->formatter->asDate(
                    $dataPrimeiraOcorrencia . ' ' . Yii::$app->timeZone
                ),
            ]
        );
    }

    public function actionMapaFocos($slug, $lat = null , $lon = null)
    {
        $emAreaTratamento = null;

        if ($lat && $lon) {
            $emAreaTratamento = FocoTransmissor::isAreaTratamento($this->module->cliente->id, $lat, $lon);
        }

        $qtdeDias = 360;

        if ($this->module->municipio->cliente) {
            $qtdeDias = Configuracao::getValorConfiguracaoParaCliente(
                Configuracao::ID_QUANTIDADE_DIAS_INFORMACAO_PUBLICA,
                $this->module->cliente->id
            );
        }

        return $this->render(
            'mapa-focos',
            [
                'cliente' => $this->module->municipio->cliente,
                'municipio' => $this->module->municipio,
                'qtdeDias' => $qtdeDias,
                'lat' => $lat,
                'lon' => $lon,
                'emAreaTratamento' => $emAreaTratamento,
            ]
        );
    }

    public function actionBuscarOcorrencia($slug, $hash = null)
    {
        $municipio = $this->module->municipio;
        $ocorrencia = $this->getOcorrencia($hash, false);
        if ($ocorrencia && $ocorrencia->municipio_id == $municipio->id) {
            return $this->redirect([
                'acompanhar-ocorrencia',
                'slug' => $slug,
                'hash' => $hash
            ]);
        }

        return $this->render(
            'buscar-ocorrencia',
            [
                'municipio' => $municipio,
                'hash' => $hash,
            ]
        );
    }

    public function actionAcompanharOcorrencia($slug, $hash)
    {
        $model = $this->getOcorrencia($hash);
        $municipio = $this->module->municipio;

        if ($model->municipio_id != $municipio->id) {
            throw new HttpException(404, 'Ocorrência não encontrada');
        }

        return $this->render(
            'acompanhar-ocorrencia',
            [
                'municipio' => $municipio,
                'model' => $model,
                'dataProvider' => new ActiveDataProvider(['query' => $model->getOcorrenciaHistoricos()]),
                'historicos' => $model->getOcorrenciaHistoricos()->all(),
            ]
        );
    }

    public function actionIsAreaTratamento($slug, $lat, $lon)
    {
        echo Json::encode(['isAreaTratamento' => FocoTransmissor::isAreaTratamento($this->module->cliente->id, $lat, $lon)]);
    }

    public function actionCoordenadaNaCidade($slug, $lat, $lon)
    {
        echo Json::encode(['coordenadaNaCidade' => $this->module->municipio->coordenadaNaCidade($lat, $lon)]);
    }

    public function actionComprovanteOcorrencia($slug, $hash)
    {
        $model = $this->getOcorrencia($hash);
        Yii::$app->response->format = 'pdf';
        $this->layout = '//print';
        return $this->render('//shared/comprovante-ocorrencia', [
            'model' => $model,
        ]);
    }

    protected function getOcorrencia($hash, $throwException = true)
    {
        $model = Ocorrencia::find()->andWhere(['hash_acesso_publico' => $hash])->one();
        if (!$model && $throwException) {
            throw new HttpException(400, 'Ôcorrência não localizada');
        }
        return $model;
    }
}
