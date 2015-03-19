<?php

namespace app\controllers;

use app\components\CRUDController;
use app\batch\controller\Batchable;
use app\models\search\BoletimRgFechamentoSearch;

class BoletimRgController extends CRUDController
{
    private $_modelSaveName;

    public function actions()
    {
        return [
            'bairroCategoria' => ['class' => 'app\components\actions\BairroCategoria'],
            'bairroQuarteiroes' => ['class' => 'app\components\actions\BairroQuarteiroes'],
            'ruas' => ['class' => 'app\components\actions\Ruas'],
            'batch' => [
                'class' => 'app\\batch\\Action',
                'modelClass' => 'app\\models\\batch\\BoletimRg',
            ]
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $actions = ['verFechamento', 'bairroCategoria', 'bairroQuarteiroes', 'ruas', 'createFechamento', 'updateFechamento', 'batch'];

        foreach ($actions as $action) {
            $behaviors['access']['only'][] = $action;
            $behaviors['access']['rules'][0]['actions'][] = $action;
        }

        return $behaviors;
    }



    public function init()
    {
        parent::init();

        if (!empty($_POST['BoletimRg']['imoveis']['exemplo'])) {
            unset($_POST['BoletimRg']['imoveis']['exemplo']);
        }
    }

    public function actionCreate()
    {
        $this->_modelSaveName = 'salvarComImoveis';

        $model = $this->buildNewModel();

        if (!$this->loadAndSaveModel($model, $_POST, ['boletim-rg/create', 'bairro_id' => isset($_POST['a']) ? $_POST['a'] : null])) {
            return $this->renderAjaxOrLayout('create', ['model' => $model]);
        }
    }

    public function actionUpdate($id)
    {
        $this->_modelSaveName = 'salvarComImoveis';

        $model = $this->findModel($id);

        if (!empty($_POST)) {

            $model = is_object($id) ? $id : $this->findModel($id);

            if (!$this->loadAndSaveModel($model, $_POST)) {
                return $this->render('update', ['model' => $model]);
            }

        } else {
            $model->popularImoveis();
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionVerFechamento($id)
    {
        $searchModel = new BoletimRgFechamentoSearch;

        $dataProvider = $searchModel->search(['boletim_rg_id' => $id]);

        return $this->renderPartial(
            '_fechamento',
            ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]
        );
    }

    public function actionCreateFechamento()
    {
        $this->_modelSaveName = 'salvarComFechamento';

        $model = $this->buildNewModel();

        if (!$this->loadAndSaveModel($model, $_POST)) {
            return $this->renderAjaxOrLayout('create-fechamento', ['model' => $model]);
        }
    }

    public function actionUpdateFechamento($id)
    {
        $this->_modelSaveName = 'salvarComFechamento';

        $model = $this->findModel($id);

        if (!empty($_POST)) {

            $model = is_object($id) ? $id : $this->findModel($id);

            if (!$this->loadAndSaveModel($model, $_POST)) {
                return $this->render('update-fechamento', ['model' => $model]);
            }

        } else {
            $model->popularFechamento();
        }

        return $this->render('update-fechamento', ['model' => $model]);
    }

    /**
     * @inheritdoc
     */
    protected function getModelSaveMethodName()
    {
        return $this->_modelSaveName;
    }
}
