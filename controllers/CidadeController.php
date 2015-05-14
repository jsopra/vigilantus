<?php
namespace app\controllers;

use Yii;
use app\components\Controller;
use app\models\Cliente;
use app\models\Configuracao;
use app\models\redis\FocoTransmissor as FocoTransmissorRedis;
use app\models\Denuncia;
use app\models\Modulo;
use app\models\FocoTransmissor;
use yii\web\UploadedFile;
use app\helpers\models\DenunciaHelper;
use yii\data\ActiveDataProvider;
use app\models\DenunciaHistorico;
use yii\helpers\Json;

class CidadeController extends Controller
{
    public function actionIndex($id)
    {
        $cliente = Cliente::find()->andWhere(['id' => $id])->one();
        if(!$cliente) {
            throw new \Exception('Município não localizado');
        }

        if(!$cliente->moduloIsHabilitado(Modulo::MODULO_DENUNCIA)) {
            throw new \Exception('Município não utiliza denúncias');
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

    public function actionDenunciar($id)
    {
        $cliente = Cliente::find()->andWhere(['id' => $id])->one();
        if(!$cliente) {
            throw new \Exception('Município não localizado');
        }

        if(!$cliente->moduloIsHabilitado(Modulo::MODULO_DENUNCIA)) {
            throw new \Exception('Município não utiliza denúncias');
        }

        Yii::$app->session->set('user.cliente', $cliente);

        $model = new Denuncia();

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
                        $model->file->saveAs(DenunciaHelper::getUploadPath() . $model->anexo);
                    }

                    Yii::$app->session->setFlash('success', 'Denúncia realizada com sucesso. Você será notificado quando a denúncia for avaliada.');

                    return $this->redirect(['cidade/acompanhar-denuncia', 'id' => $id, 'hash' => $model->hash_acesso_publico]);
                }
                else {
                    Yii::$app->session->setFlash('error', 'Erro ao salvar a denúncia.');
                }
            }
        }

        return $this->render(
            'denuncia',
            [
                'cliente' => $cliente,
                'municipio' => $cliente->municipio,
                'model' => $model,
            ]
        );
    }

    public function actionAcompanharDenuncia($id, $hash)
    {
        $cliente = Cliente::find()->andWhere(['id' => $id])->one();
        if(!$cliente) {
            throw new \yii\web\HttpException(400, 'Município não localizado', 405);
        }

        if(!$cliente->moduloIsHabilitado(Modulo::MODULO_DENUNCIA)) {
            throw new \yii\web\HttpException(400, 'Município não utiliza denúncias', 405);
        }

        Yii::$app->session->set('user.cliente', $cliente);

        $model = Denuncia::find()->andWhere(['hash_acesso_publico' => $hash])->one();
        if(!$model) {
            throw new \yii\web\HttpException(400, 'Denúncia não localizada', 405);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => DenunciaHistorico::find()->daDenuncia($model->id),
        ]);

        return $this->render(
            'acompanhar-denuncia',
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

        if(!$cliente->moduloIsHabilitado(Modulo::MODULO_DENUNCIA)) {
            throw new \Exception('Município não utiliza denúncias');
        }

        Yii::$app->session->set('user.cliente', $cliente);

        echo Json::encode(['isAreaTratamento' => FocoTransmissor::isAreaTratamento($cliente->id, $lat, $lon)]);
    }

    public function actionComprovanteDenuncia($id, $hash)
    {
        $model = Denuncia::find()->andWhere(['hash_acesso_publico' => $hash])->one();
        if(!$model) {
            throw new \yii\web\HttpException(400, 'Denúncia não localizada', 405);
        }

        Yii::$app->response->format = 'pdf';
        $this->layout = '//print';
        return $this->render('//shared/comprovante-denuncia', [
            'model' => $model,
        ]);
    }
}
