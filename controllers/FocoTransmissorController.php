<?php

namespace app\controllers;

use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\CRUDController;
use app\batch\controller\Batchable;
use app\models\FocoTransmissor;

class FocoTransmissorController extends CRUDController
{
    public function actions()
    {
        return [
            'bairroCategoria' => ['class' => 'app\components\actions\BairroCategoria'],
            'bairroQuarteiroes' => ['class' => 'app\components\actions\BairroQuarteiroes'],
            'ruas' => ['class' => 'app\components\actions\Ruas'],
            'imoveis' => ['class' => 'app\components\actions\Imoveis'],
            'batch' => [
                'class' => 'app\\batch\\Action',
                'modelClass' => 'app\\models\\batch\\FocosTransmissor',
            ]
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'delete', 'index', 'update', 'imoveis', 'batch'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'index', 'imoveis', 'bairroCategoria', 'bairroQuarteiroes', 'ruas', 'batch'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!empty($_POST)) {

            $model = is_object($id) ? $id : $this->findModel($id);

            if (!$this->loadAndSaveModel($model, $_POST)) {
                return $this->render('update', ['model' => $model]);
            }

        } else {
            $model->popularBairro();
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * @inheritdoc
     */
    protected function loadAndSaveModel(FocoTransmissor $model, $data = null, $redirect = ['index'])
    {
        if(isset($data['FocoTransmissor']['imovel_id']) && is_string($data['FocoTransmissor']['imovel_id'])) {
            $data['FocoTransmissor']['planilha_endereco'] = $data['FocoTransmissor']['imovel_id'];
            unset($data['FocoTransmissor']['imovel_id']);
        }

        if (!empty($data) && $model->load($data)) {

            $isNewRecord = $model->isNewRecord;

            if ($isNewRecord && $model->hasAttribute('inserido_por')) {
                $model->inserido_por = Yii::$app->user->identity->id;
            }
            elseif ($model->hasAttribute('atualizado_por')) {
                $model->atualizado_por = Yii::$app->user->identity->id;
            }

            $saveMethodName = $this->getModelSaveMethodName();

            if ($model->$saveMethodName()) {

                $message = $isNewRecord ? $this->createFlashMessage : $this->updateFlashMessage;

                Yii::$app->session->setFlash('success', $message);

                return $this->redirect($redirect);
            }
        }

        return false;
    }
}
