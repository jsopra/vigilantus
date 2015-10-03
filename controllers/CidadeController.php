<?php
namespace app\controllers;

use Yii;
use app\components\Controller;
use app\helpers\models\OcorrenciaHelper;
use app\models\Cliente;
use app\models\Configuracao;
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
    /**
     * @var Cliente
     */
    protected $cliente;

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $usuario = Yii::$app->user->identity;

        if ($usuario && $usuario->usuario_role_id == UsuarioRole::ROOT) {
            $usuario->cliente_id = $this->getCliente()->id;
            $usuario->update(false, ['cliente_id']);
        }

        return true;
    }

    protected function getCliente()
    {
        $cliente = Cliente::findOne(Yii::$app->request->get('id', 0));

        if (!$cliente) {
            throw new HttpException(400, 'Município não localizado', 405);
        }

        if (!$cliente->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA)) {
            throw new HttpException(400, 'Município não utiliza ocorrências', 405);
        }

        return $cliente;
    }

    public function actionView($rotulo)
    {
        $cliente = Cliente::find()->doRotulo($rotulo)->one();

        if (!$cliente) {
            throw new HttpException(404, 'Município não encontrado');
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

        return $this->render(
            'view',
            [
                'cliente' => $cliente,
                'municipio' => $cliente->municipio,
                'numeroOcorrenciasRecebidas' => $numeroOcorrenciasRecebidas,
                'percentualOcorrenciasAtendidas' => round($percentualOcorrencias),
                'dataPrimeiraOcorrencia' => Yii::$app->formatter->asDate(
                    $primeiraOcorrencia->data_criacao . ' ' . Yii::$app->timeZone
                ),
            ]
        );
    }

    public function actionIndex($id)
    {
        $cliente = $this->getCliente();
        $this->redirect(['cidade/view', 'id' => $id, 'rotulo' => $cliente->rotulo]);
    }

    public function actionMapaFocos($id)
    {
        return $this->render(
            'mapa-focos',
            [
                'cliente' => $this->getCliente(),
                'municipio' => $this->getCliente()->municipio,
                'qtdeDias' => Configuracao::getValorConfiguracaoParaCliente(
                    Configuracao::ID_QUANTIDADE_DIAS_INFORMACAO_PUBLICA,
                    $this->getCliente()->id
                ),
            ]
        );
    }

    public function actionBuscarOcorrencia($id, $hash = null)
    {
        if ($this->getOcorrencia($hash, false)) {
            return $this->redirect([
                'acompanhar-ocorrencia',
                'id' => $id,
                'hash' => $hash
            ]);
        }

        return $this->render(
            'buscar-ocorrencia',
            [
                'cliente' => $this->getCliente(),
                'municipio' => $this->getCliente()->municipio,
                'hash' => $hash,
            ]
        );
    }

    public function actionAcompanharOcorrencia($id, $hash)
    {
        $model = $this->getOcorrencia($hash);

        return $this->render(
            'acompanhar-ocorrencia',
            [
                'cliente' => $this->getCliente(),
                'municipio' => $this->getCliente()->municipio,
                'model' => $model,
                'dataProvider' => new ActiveDataProvider(['query' => $model->getOcorrenciaHistoricos()]),
                'historicos' => $model->getOcorrenciaHistoricos()->all(),
            ]
        );
    }

    public function actionIsAreaTratamento($id, $lat, $lon)
    {
        echo Json::encode(['isAreaTratamento' => FocoTransmissor::isAreaTratamento($this->getCliente()->id, $lat, $lon)]);
    }

    public function actionCoordenadaNaCidade($id, $lat, $lon)
    {
        echo Json::encode(['coordenadaNaCidade' => $this->getCliente()->municipio->coordenadaNaCidade($lat, $lon)]);
    }

    public function actionComprovanteOcorrencia($id, $hash)
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
            throw new HttpException(400, 'Ôcorrência não localizada', 405);
        }
        return $model;
    }
}
