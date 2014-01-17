<?php
namespace app\components;

use Yii;
use yii\db\Expression;
use yii\web\VerbFilter;

class CRUDController extends Controller
{
    /**
     * @var string
     */
    protected $createFlashMessage = 'O cadastro foi realizado com sucesso.';
    
    /**
     * @var string
     */
    protected $updateFlashMessage = 'O registro foi atualizado com sucesso.';
    
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => '\yii\web\AccessControl',
                'only' => ['create', 'delete', 'index', 'update'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'index'],
                        'roles' => ['Administrador'],
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
        $searchModelClass = $this->getSearchModelClassName();
        $searchModel = new $searchModelClass;
        $dataProvider = $searchModel->search($_GET);
        
        return $this->render(
            'index',
            ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]
        );
    }
    
    public function actionCreate()
    {
        $model = $this->buildNewModel();
        //$model->scenario = 'insert';
        
        if (!$this->loadAndSaveModel($model, $_POST)) {
            return $this->render('create', ['model' => $model]);
        }
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
     * @inheritdoc
     */
    protected function loadAndSaveModel(ActiveRecord $model, $data = null)
    {
        if ($model->load($_POST)) {

            $isNewRecord = $model->isNewRecord;
            
            if ($isNewRecord && $model->hasAttribute('inserido_por')) {
                $model->inserido_por = Yii::$app->user->identity->id;
            } elseif ($model->hasAttribute('atualizado_por')) {
                $model->atualizado_por = Yii::$app->user->identity->id;
            }
            
            if ($model->save()) {
                
                $message = $isNewRecord ? $this->createFlashMessage : $this->updateFlashMessage;
                
                Yii::$app->session->setFlash('success', $message);
                
                return $this->redirect(['index']);
            }
        }
    }
    
    /**
     * @return \app\components\ActiveRecord
     */
    protected function buildNewModel()
    {
        $class = $this->getModelClassName();
        return new $class;
    }
}
