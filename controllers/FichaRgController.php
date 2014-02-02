<?php

namespace app\controllers;

use Yii;
use yii\db\Expression;
use yii\web\AccessControl;
use app\components\Controller;
use yii\web\VerbFilter;
use app\models\search\BoletimRgSearch;
use app\models\search\BoletimRgFechamentoSearch;
use app\models\BoletimRg;
use app\models\search\BoletimRgFechamento;

class FichaRgController extends Controller
{
    public function actions()
    {
        return [
            'bairroCategoria' => ['class' => 'app\components\actions\BairroCategoria'],
            'bairroQuarteiroes' => ['class' => 'app\components\actions\BairroQuarteiroes'],
            'bairroRuas' => ['class' => 'app\components\actions\BairroRuas'],
        ];
    }
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => '\yii\web\AccessControl',
                'only' => ['create', 'delete', 'index', 'update', 'fechamento', 'bairroCategoria', 'bairroQuarteiroes', 'bairroRuas'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'index', 'fechamento', 'bairroCategoria', 'bairroQuarteiroes', 'bairroRuas'],
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
	
    public function actionIndex()
    {
        $searchModel = new BoletimRgSearch;
        $dataProvider = $searchModel->search($_GET);
        
        return $this->render(
            'index',
            ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]
        );
    }
    
    public function actionCreate()
    {
        $model = $this->buildNewModel();
        
        $model->municipio_id = 1; //@todo fix
        
        if ($model->load($_POST) && $model->validate()) {

            $isNewRecord = $model->isNewRecord;
            
            if ($isNewRecord && $model->hasAttribute('inserido_por'))
                $model->inserido_por = Yii::$app->user->identity->id;
            
            elseif ($model->hasAttribute('atualizado_por'))
                $model->atualizado_por = Yii::$app->user->identity->id;
            
            if ($model->save()) {
                
                $message = $isNewRecord ? $this->createFlashMessage : $this->updateFlashMessage;
                
                Yii::$app->session->setFlash('success', $message);
                
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //$model->scenario = 'update';

        if (!$this->loadAndSaveModel($model, $_POST)) {
            return $this->render('update', ['model' => $model]);
        }
    }
    
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if ($model->hasAttribute('excluido')) {
            
            $model->excluido = true;
            $updatedAttributes = ['excluido'];
            
            if ($model->hasAttribute('excluido_por')) {
                $model->excluido_por = Yii::$app->user->id;
                $updatedAttributes[] = 'excluido_por';
            }
            
            if ($model->hasAttribute('data_exclusao')) {
                $model->data_exclusao = new Expression('NOW()');
                $updatedAttributes[] = 'data_exclusao';
            }
            
            $runValidations = false;
            
            $model->update($runValidations, $updatedAttributes);
            
        } else {
            $model->delete();
        }
        
        $this->redirect(['index']);
    }
    
    public function actionFechamento($id) {
        
        $this->layout = false;
     
        $searchModel = new BoletimRgFechamentoSearch;
        $dataProvider = $searchModel->search(['BoletimRgFechamento' => ['boletim_rg_id' => $id]]);
        
        return $this->render(
            '_fechamento',
            ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]
        );
    }
    
    /**
     * @return string
     */
    protected function getSearchModelClassName()
    {
        $modelName = explode('\\', $this->getModelClassName());
        
        $modelName[count($modelName) - 2] .= '\\search';
        $modelName[count($modelName) - 1] .= 'Search';
        
        return implode('\\', $modelName);
    }
    
    /**
     * @return string
     */
    protected function getModelDescription()
    {
        return $this->getModelClassName();
    }
    
    /**
     * @return \app\components\ActiveRecord
     */
    protected function buildNewModel()
    {
        return new BoletimRg;
    }
    
    /**
	 * Finds the model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return BairroTipo the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
    protected function findModel($id)
    {

        if (($model = BoletimRg::find(intval($id))) !== null)
            return $model;

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}