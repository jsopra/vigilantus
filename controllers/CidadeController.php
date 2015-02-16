<?php
namespace app\controllers;

use Yii;
use app\components\Controller;
use app\models\Cliente;
use app\models\redis\FocoTransmissor as FocoTransmissorRedis;
use app\models\Denuncia;
use app\models\Modulo;
use yii\web\UploadedFile;
use app\helpers\models\DenunciaHelper;

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

                    return $this->redirect(['cidade/index', 'id' => $id]);
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
            ]
        );
    }
}
