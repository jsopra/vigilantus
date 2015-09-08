<?php
namespace app\controllers;

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
        $model = new OcorrenciaForm;
        $model->clearSession();

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->scenario = OcorrenciaForm::SCENARIO_WIZARD_LOCAL;

            if ($model->validate(OcorrenciaForm::SCENARIO_WIZARD_LOCAL) && $model->persistSession()) {
                $this->redirect(['registrar-ocorrencia/detalhes', 'id' => $id]);
                Yii::app()->end();
            }
        }

        return $this->render(
            'index',
            [
                'cliente' => $this->cliente,
                'municipio' => $this->cliente->municipio,
                'model' => $model,
                'activeTab' => 0
            ]
        );
    }

    public function actionDetalhes($id)
    {
        $model = new OcorrenciaForm;
        $model->loadFromSession();

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->scenario = OcorrenciaForm::SCENARIO_WIZARD_DETALHES;

            if ($model->validate(OcorrenciaForm::SCENARIO_WIZARD_DETALHES) && $model->persistSession()) {
                $this->redirect(['registrar-ocorrencia/identificacao', 'id' => $id]);
                Yii::app()->end();
            }
        }

        return $this->render(
            'detalhes',
            [
                'cliente' => $this->cliente,
                'municipio' => $this->cliente->municipio,
                'model' => $model,
                'activeTab' => 1
            ]
        );
    }

    public function actionIdentificacao($id)
    {
        $model = new OcorrenciaForm;
        $model->loadFromSession();

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->cliente_id = $id;
            $model->scenario = OcorrenciaForm::SCENARIO_WIZARD_IDENTIFICACAO;

            if ($model->validate(OcorrenciaForm::SCENARIO_WIZARD_IDENTIFICACAO) && $model->persistSession()) {
                if($ocorrencia = $model->save()) {
                    Yii::$app->session->setFlash('success', 'Ocorrência enviada com sucesso. Você será notificado quando ela for avaliada.');
                    return $this->redirect(['cidade/acompanhar-ocorrencia', 'id' => $id, 'hash' => $ocorrencia->hash_acesso_publico]);
                    Yii::app()->end();
                } else {
                    Yii::$app->session->setFlash('error', 'Erro ao salvar a ocorrência.');
                }
            } else {
                die(var_dump($model->errors));
            }
        }

        return $this->render(
            'identificacao',
            [
                'cliente' => $this->cliente,
                'municipio' => $this->cliente->municipio,
                'model' => $model,
                'activeTab' => 2
            ]
        );
    }
}
