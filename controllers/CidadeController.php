<?php
namespace app\controllers;

use Yii;
use app\components\Controller;
use app\models\Cliente;
use app\models\Configuracao;
use app\models\redis\FocoTransmissor as FocoTransmissorRedis;
use app\models\Denuncia;
use app\models\Modulo;
use yii\web\UploadedFile;
use app\helpers\models\DenunciaHelper;
use yii\data\ActiveDataProvider;
use app\models\DenunciaHistorico;

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
            'index',
            [
                'cliente' => $cliente,
                'municipio' => $cliente->municipio,
                'url' => ['kml/focos', 'clienteId' => $cliente->id, 'informacaoPublica' => true],
                'viewPartial' => '_focos',
                'model' => $model,
                'qtdeDias' => Configuracao::getValorConfiguracaoParaCliente(Configuracao::ID_QUANTIDADE_DIAS_INFORMACAO_PUBLICA, $cliente->id),
            ]
        );
    }

    public function actionAcompanharDenuncia($id, $hash)
    {
        $cliente = Cliente::find()->andWhere(['id' => $id])->one();
        if(!$cliente) {
            throw new \Exception('Município não localizado');
        }

        if(!$cliente->moduloIsHabilitado(Modulo::MODULO_DENUNCIA)) {
            throw new \Exception('Município não utiliza denúncias');
        }

        Yii::$app->session->set('user.cliente', $cliente);

        $model = Denuncia::find()->andWhere(['hash_acesso_publico' => $hash])->one();
        if(!$model) {
            throw new \Exception('Denúncia não localizada');
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
}
