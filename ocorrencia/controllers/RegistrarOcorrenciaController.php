<?php
namespace app\ocorrencia\controllers;

use Yii;
use app\components\Controller;
use app\models\Cliente;
use app\models\Configuracao;
use app\models\Ocorrencia;
use app\models\Modulo;
use app\models\UsuarioRole;
use app\forms\OcorrenciaForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\HttpException;
use yii\web\UploadedFile;

class RegistrarOcorrenciaController extends Controller
{
    public function init()
    {
        parent::init();

        if (Yii::$app->user->isGuest) {
            $this->layout = '//website';
        }
    }

    public function actionIndex($slug)
    {
        $model = new OcorrenciaForm;
        $model->clearSession();

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->scenario = OcorrenciaForm::SCENARIO_WIZARD_LOCAL;

            if ($model->validate(OcorrenciaForm::SCENARIO_WIZARD_LOCAL) && $model->persistSession()) {
                return $this->redirect(['registrar-ocorrencia/detalhes', 'slug' => $slug]);
            }
        }

        return $this->render(
            'index',
            [
                'municipio' => $this->module->municipio,
                'model' => $model,
                'activeTab' => 0
            ]
        );
    }

    public function actionDetalhes($slug)
    {
        $model = new OcorrenciaForm;
        $model->loadFromSession();

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->scenario = OcorrenciaForm::SCENARIO_WIZARD_DETALHES;

            if ($model->validate(OcorrenciaForm::SCENARIO_WIZARD_DETALHES) && $model->persistSession()) {
                return $this->redirect(['registrar-ocorrencia/identificacao', 'slug' => $slug]);
            }
        }

        return $this->render(
            'detalhes',
            [
                'municipio' => $this->module->municipio,
                'model' => $model,
                'activeTab' => 1
            ]
        );
    }

    public function actionIdentificacao($slug)
    {
        $model = new OcorrenciaForm;
        $model->loadFromSession();

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->municipio_id = $this->module->municipio->id;
            $model->cliente_id = $this->module->municipio->cliente ? $this->module->municipio->cliente->id : null;
            $model->scenario = OcorrenciaForm::SCENARIO_WIZARD_IDENTIFICACAO;

            if ($model->validate(OcorrenciaForm::SCENARIO_WIZARD_IDENTIFICACAO) && $model->persistSession()) {
                if ($ocorrencia = $model->save()) {
                    Yii::$app->session->setFlash('success', 'Ocorrência enviada com sucesso. Você será notificado quando ela for avaliada.');
                    return $this->redirect(['cidade/acompanhar-ocorrencia', 'slug' => $slug, 'hash' => $ocorrencia->hash_acesso_publico]);
                } else {
                    Yii::error('Erro ao salvar a ocorrência: ' . Json::encode($model->errors), __METHOD__);
                    Yii::$app->session->setFlash('error', 'Erro ao salvar a ocorrência.');
                }
            }
        }

        return $this->render(
            'identificacao',
            [
                'municipio' => $this->module->municipio,
                'model' => $model,
                'activeTab' => 2
            ]
        );
    }

    /**
     * @FIXME Isso deveria vir da https://api.vigilantus.com.br/v1/bairros/:id
     * mas infelizmente a Getup não possui Vhosts para isso.
     */
    public function actionCoordenadasBairro($slug, $bairro_id)
    {
        $bairro = $this->module->municipio->getBairros()->andWhere(['id' => $bairro_id])->one();

        if (!$bairro) {
            throw new HttpException(404, 'Bairro não encontrado.');
        }

        return Json::encode($bairro->getCentro());
    }
}
