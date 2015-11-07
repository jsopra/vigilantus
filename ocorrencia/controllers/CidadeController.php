<?php
namespace app\ocorrencia\controllers;

use Yii;
use app\components\Controller;
use app\helpers\models\OcorrenciaHelper;
use app\models\Cliente;
use app\models\Configuracao;
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
    public function actionIndex()
    {
        return $this->render(
            'index',
            ['query' => Municipio::find()->clientes()->porEstado()->ordemAlfabetica()]
        );
    }

    public function actionView($slug)
    {
        $municipio = Municipio::find()->where(['slug' => $slug])->one();
        $cliente = Cliente::find()->doMunicipio($municipio->id)->one();

        if (!$municipio) {
            throw new HttpException(404, 'Município não encontrado');
        }

        if (!$cliente) {
            throw new HttpException(404, 'Município não utiliza o software');
        }

        if (!$cliente->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA)) {
            throw new HttpException(404, 'Município não recebe ocorrências por este canal');
        }

        $numeroOcorrenciasRecebidas = Ocorrencia::find()
            ->doCliente($cliente)
            ->count()
        ;
        $numeroOcorrenciasAtendidas = Ocorrencia::find()
            ->doCliente($cliente)
            ->fechada()
            ->count()
        ;
        $primeiraOcorrencia = Ocorrencia::find()
            ->doCliente($cliente)
            ->orderBy('data_criacao ASC')
            ->limit(1)
            ->one()
        ;
        $percentualOcorrencias = $numeroOcorrenciasRecebidas ? $numeroOcorrenciasAtendidas / $numeroOcorrenciasRecebidas * 100 : 0;
        $dataPrimeiraOcorrencia = $primeiraOcorrencia ? $primeiraOcorrencia->data_criacao : $cliente->data_cadastro;

        return $this->render(
            'view',
            [
                'cliente' => $cliente,
                'municipio' => $cliente->municipio,
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

        if($lat && $lon) {
            $emAreaTratamento = FocoTransmissor::isAreaTratamento($this->module->cliente->id, $lat, $lon);
        }

        return $this->render(
            'mapa-focos',
            [
                'cliente' => $this->module->cliente,
                'municipio' => $this->module->municipio,
                'qtdeDias' => Configuracao::getValorConfiguracaoParaCliente(
                    Configuracao::ID_QUANTIDADE_DIAS_INFORMACAO_PUBLICA,
                    $this->module->cliente->id
                ),
                'lat' => $lat,
                'lon' => $lon,
                'emAreaTratamento' => $emAreaTratamento,
            ]
        );
    }

    public function actionBuscarOcorrencia($slug, $hash = null)
    {
        if ($this->getOcorrencia($hash, false)) {
            return $this->redirect([
                'acompanhar-ocorrencia',
                'slug' => $slug,
                'hash' => $hash
            ]);
        }

        return $this->render(
            'buscar-ocorrencia',
            [
                'cliente' => $this->module->cliente,
                'municipio' => $this->module->municipio,
                'hash' => $hash,
            ]
        );
    }

    public function actionAcompanharOcorrencia($slug, $hash)
    {
        $model = $this->getOcorrencia($hash);

        return $this->render(
            'acompanhar-ocorrencia',
            [
                'cliente' => $this->module->cliente,
                'municipio' => $this->module->municipio,
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
