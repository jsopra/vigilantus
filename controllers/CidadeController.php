<?php
namespace app\controllers;

use Yii;
use app\components\Controller;
use app\models\Municipio;
use app\models\redis\FocoTransmissor as FocoTransmissorRedis;
use app\models\Denuncia;
use yii\web\UploadedFile;
use app\helpers\models\DenunciaHelper;

class CidadeController extends Controller
{
    public function actionIndex($id)
    {
        $municipio = Municipio::find()->andWhere(['id' => $id])->one();
        if(!$municipio) {
            throw new \Exception('Município não localizado');
        }

        $model = new Denuncia();

        if (Yii::$app->request->post()) {

            $model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');

            if($model->validate()) {

                if($model->file) {
                    $model->nome_original_anexo = $model->file->baseName . '.' . $model->file->extension;
                    $model->anexo = time() . '.' . $model->file->extension;
                }

                if ($model->save()) {

                    if($model->file) {
                        $model->file->saveAs(DenunciaHelper::getUploadPath(true) . $model->anexo);
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
                'municipio' => $municipio,
                'dados' => FocoTransmissorRedis::find()->doMunicipio($municipio->id)->informacaoPublica()->all(),
                'viewPartial' => '_focos',
                'model' => $model,
            ]
        );
    }
}
