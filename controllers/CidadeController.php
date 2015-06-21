<?php
namespace app\controllers;

use Yii;
use app\components\Controller;
use app\models\Cliente;
use app\models\Configuracao;
use app\models\redis\FocoTransmissor as FocoTransmissorRedis;
use app\models\Ocorrencia;
use app\models\Modulo;
use app\models\FocoTransmissor;
use yii\web\UploadedFile;
use app\helpers\models\OcorrenciaHelper;
use yii\data\ActiveDataProvider;
use app\models\OcorrenciaHistorico;
use yii\helpers\Json;

class CidadeController extends Controller
{
    public function actionIndex($id)
    {
        $cliente = Cliente::find()->andWhere(['id' => $id])->one();
        if(!$cliente) {
            throw new \Exception('Município não localizado');
        }

        if(!$cliente->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA)) {
            throw new \Exception('Município não utiliza ocorrências');
        }

        Yii::$app->session->set('user.cliente', $cliente);

        return $this->render(
            'index',
            [
                'cliente' => $cliente,
                'municipio' => $cliente->municipio,
                'qtdeDias' => Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_INFORMACAO_PUBLICA, $cliente->id),
            ]
        );
    }

    public function actionRegistrarOcorrencia($id)
    {
        $cliente = Cliente::find()->andWhere(['id' => $id])->one();
        if(!$cliente) {
            throw new \Exception('Município não localizado');
        }

        if(!$cliente->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA)) {
            throw new \Exception('Município não utiliza ocorrências');
        }

        Yii::$app->session->set('user.cliente', $cliente);

        $model = new Ocorrencia;

        if (Yii::$app->request->post()) {

            $model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->cliente_id = $cliente->id;

            if($model->validate()) {

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
                'cliente' => $cliente,
                'municipio' => $cliente->municipio,
                'model' => $model,
            ]
        );
    }

    public function actionAcompanharOcorrencia($id, $hash)
    {
        $cliente = Cliente::find()->andWhere(['id' => $id])->one();
        if(!$cliente) {
            throw new \yii\web\HttpException(400, 'Município não localizado', 405);
        }

        if(!$cliente->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA)) {
            throw new \yii\web\HttpException(400, 'Município não utiliza ocorrências', 405);
        }

        Yii::$app->session->set('user.cliente', $cliente);

        $model = Ocorrencia::find()->andWhere(['hash_acesso_publico' => $hash])->one();
        if(!$model) {
            throw new \yii\web\HttpException(400, 'Ocorrência não localizada', 405);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => OcorrenciaHistorico::find()->daOcorrencia($model->id),
        ]);

        return $this->render(
            'acompanhar-ocorrencia',
            [
                'cliente' => $cliente,
                'municipio' => $cliente->municipio,
                'model' => $model,
                'dataProvider' => $dataProvider
            ]
        );
    }

    public function actionIsAreaTratamento($id, $lat, $lon)
    {
        $cliente = Cliente::find()->andWhere(['id' => $id])->one();
        if(!$cliente) {
            throw new \Exception('Município não localizado');
        }

        if(!$cliente->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA)) {
            throw new \Exception('Município não utiliza ocorrências');
        }

        Yii::$app->session->set('user.cliente', $cliente);

        echo Json::encode(['isAreaTratamento' => FocoTransmissor::isAreaTratamento($cliente->id, $lat, $lon)]);
    }

    public function actionCoordenadaNaCidade($id, $lat, $lon)
    {
        $cliente = Cliente::find()->andWhere(['id' => $id])->one();
        if(!$cliente) {
            throw new \Exception('Município não localizado');
        }

        if(!$cliente->moduloIsHabilitado(Modulo::MODULO_OCORRENCIA)) {
            throw new \Exception('Município não utiliza ocorrências');
        }

        Yii::$app->session->set('user.cliente', $cliente);

        echo Json::encode(['coordenadaNaCidade' => $cliente->municipio->coordenadaNaCidade($lat, $lon)]);
    }

    public function actionComprovanteOcorrencia($id, $hash)
    {
        $model = Ocorrencia::find()->andWhere(['hash_acesso_publico' => $hash])->one();
        if(!$model) {
            throw new \yii\web\HttpException(400, 'Ôcorrência não localizada', 405);
        }

        Yii::$app->response->format = 'pdf';
        $this->layout = '//print';
        return $this->render('//shared/comprovante-ocorrencia', [
            'model' => $model,
        ]);
    }
}
