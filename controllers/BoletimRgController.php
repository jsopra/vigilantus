<?php

namespace app\controllers;

use Yii;
use yii\db\Expression;
use yii\web\AccessControl;
use app\components\CRUDController;
use yii\web\VerbFilter;
use app\models\search\BoletimRgSearch;
use app\models\search\BoletimRgFechamentoSearch;
use app\models\BoletimRg;
use app\models\search\BoletimRgFechamento;

class BoletimRgController extends CRUDController
{
    public function actions()
    {
        return [
            'bairroCategoria' => ['class' => 'app\components\actions\BairroCategoria'],
            'bairroQuarteiroes' => ['class' => 'app\components\actions\BairroQuarteiroes'],
            'bairroRuas' => ['class' => 'app\components\actions\Ruas'],
        ];
    }
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => '\yii\web\AccessControl',
                'only' => ['create', 'delete', 'index', 'update', 'verFechamento', 'bairroCategoria', 'bairroQuarteiroes', 'bairroRuas'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'index', 'verFechamento', 'bairroCategoria', 'bairroQuarteiroes', 'bairroRuas'],
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
    
    public function actionCreate()
    {
        if(isset($_POST['BoletimRg']['imoveis']['exemplo']))
            unset($_POST['BoletimRg']['imoveis']['exemplo']);
        
        $model = $this->buildNewModel();
        //$model->scenario = 'insert';

        if (!$this->loadAndSaveModel($model, $_POST)) { 
            return $this->render('create', ['model' => $model]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if(!empty($_POST)) {
            if(isset($_POST['BoletimRg']['imoveis']['exemplo'])) 
                unset($_POST['BoletimRg']['imoveis']['exemplo']);

            $model = is_object($id) ? $id : $this->findModel($id);
            //$model->scenario = 'update';

            if (!$this->loadAndSaveModel($model, $_POST)) { 
                return $this->render('update', ['model' => $model]);
            }
            
        }
        else {
            $model->bairro_quarteirao_numero = $model->quarteirao->numero_quarteirao;
            $model->populaImoveis();
        }
        
        return $this->render('update', ['model' => $model]);
    }
    
    public function actionVerFechamento($id) {
        
        $this->layout = false;
     
        $searchModel = new BoletimRgFechamentoSearch;

        $dataProvider = $searchModel->search(['boletim_rg_id' => $id]);
        
        return $this->render(
            '_fechamento',
            ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]
        );
    }
}