<?php

namespace app\controllers;

use app\components\CRUDController;
use app\models\search\BoletimRgFechamentoSearch;

class BoletimRgController extends CRUDController
{
    public function actions()
    {
        return [
            'bairroCategoria' => ['class' => 'app\components\actions\BairroCategoria'],
            'bairroQuarteiroes' => ['class' => 'app\components\actions\BairroQuarteiroes'],
            'ruas' => ['class' => 'app\components\actions\Ruas'],
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $actions = ['verFechamento', 'bairroCategoria', 'bairroQuarteiroes', 'ruas'];

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

    public function actionUpdate($id)
    {
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

    /**
     * @inheritdoc
     */
    protected function getModelSaveMethodName()
    {
        return 'salvarComImoveis';
    }
}
