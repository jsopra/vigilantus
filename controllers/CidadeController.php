<?php
namespace app\controllers;

use Yii;
use app\components\Controller;
use app\helpers\models\OcorrenciaHelper;
use app\models\Cliente;
use app\models\Configuracao;
use app\models\Ocorrencia;
use app\models\OcorrenciaHistorico;
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

        if ($action->id == 'comprovante-ocorrencia') {
            return true;
        }

        $id = Yii::$app->request->get('id', 0);
        $this->cliente = Cliente::findOne($id);

        if (!$this->cliente) {
            throw new HttpException(400, 'Município não localizado', 405);
        }

        if (!$this->cliente->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA)) {
            throw new HttpException(400, 'Município não utiliza ocorrências', 405);
        }

        $usuario = Yii::$app->user->identity;

        if ($usuario && $usuario->usuario_role_id == UsuarioRole::ROOT) {
            $usuario->cliente_id = $this->cliente->id;
            $usuario->update(false, ['cliente_id']);
        }

        return true;
    }

    public function actionIndex($id)
    {
        return $this->render(
            'index',
            [
                'cliente' => $this->cliente,
                'municipio' => $this->cliente->municipio,
                'qtdeDias' => Configuracao::getValorConfiguracaoParaCliente(
                    Configuracao::ID_QUANTIDADE_DIAS_INFORMACAO_PUBLICA,
                    $this->cliente->id
                ),
            ]
        );
    }

    public function actionRegistrarOcorrencia($id)
    {
        $model = new Ocorrencia;

        if (Yii::$app->request->post()) {

            $model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->cliente_id = $this->cliente->id;

            if ($model->validate()) {

                if($model->file) {
                    $model->nome_original_anexo = $model->file->baseName . '.' . $model->file->extension;
                    $model->anexo = time() . '.' . $model->file->extension;
                }

                if ($model->save()) {

                    if($model->file) {
                        $model->file->saveAs(OcorrenciaHelper::getUploadPath() . $model->anexo);
                    }

                    Yii::$app->session->setFlash('success', 'Ocorrência enviada com sucesso. Você será notificado quando ela for avaliada.');

                    return $this->redirect(['cidade/acompanhar-ocorrencia', 'id' => $id, 'hash' => $model->hash_acesso_publico]);
                }
                else {
                    Yii::$app->session->setFlash('error', 'Erro ao salvar a ocorrência.');
                }
            }
        }

        return $this->render(
            'ocorrencia',
            [
                'cliente' => $this->cliente,
                'municipio' => $this->cliente->municipio,
                'model' => $model,
            ]
        );
    }

    public function actionAcompanharOcorrencia($id, $hash)
    {
        $model = Ocorrencia::find()->andWhere(['hash_acesso_publico' => $hash])->one();
        if(!$model) {
            throw new HttpException(400, 'Ocorrência não localizada', 405);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => OcorrenciaHistorico::find()->daOcorrencia($model->id),
        ]);

        return $this->render(
            'acompanhar-ocorrencia',
            [
                'cliente' => $this->cliente,
                'municipio' => $this->cliente->municipio,
                'model' => $model,
                'dataProvider' => $dataProvider
            ]
        );
    }

    public function actionIsAreaTratamento($id, $lat, $lon)
    {
        echo Json::encode(['isAreaTratamento' => FocoTransmissor::isAreaTratamento($this->cliente->id, $lat, $lon)]);
    }

    public function actionCoordenadaNaCidade($id, $lat, $lon)
    {
        echo Json::encode(['coordenadaNaCidade' => $this->cliente->municipio->coordenadaNaCidade($lat, $lon)]);
    }

    public function actionComprovanteOcorrencia($id, $hash)
    {
        $model = Ocorrencia::find()->andWhere(['hash_acesso_publico' => $hash])->one();
        if(!$model) {
            throw new HttpException(400, 'Ôcorrência não localizada', 405);
        }

        Yii::$app->response->format = 'pdf';
        $this->layout = '//print';
        return $this->render('//shared/comprovante-ocorrencia', [
            'model' => $model,
        ]);
    }
}
