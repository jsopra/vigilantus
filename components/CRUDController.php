<?php
namespace app\components;

use app\widgets\Alert;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

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
     * @var string
     */
    protected $deleteFlashMessage = 'O registro foi excluído com sucesso.';
    
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'delete', 'index', 'update'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'index'],
                        'roles' => ['Usuario'],
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
        
        return $this->renderAjaxOrLayout(
            'index',
            ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]
        );
    }
    
    public function actionCreate()
    {
        $model = $this->buildNewModel();

        if (!$this->loadAndSaveModel($model, $_POST)) { 
            return $this->renderAjaxOrLayout('create', ['model' => $model]);
        }
    }

    public function actionView($id)
    {
        return $this->renderAjaxOrLayout('view', ['model' => $this->findModel($id)]);
    }

    public function actionUpdate($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);

        if (!$this->loadAndSaveModel($model, $_POST)) { 
            return $this->renderAjaxOrLayout('update', ['model' => $model]);
        }
    }
    
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        $this->disableOrDelete($model);
        
        if (!Yii::$app->request->isAjax) 
            Yii::$app->session->setFlash('success', $this->deleteFlashMessage);
        
        return $this->redirect(['index']);
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
     * Se o modelo tiver um atributo 'excluido', desativa ao invés de excluir
     * @param ActiveRecord $model
     * @return integer Linhas excluídas ou atualizadas
     */
    protected function disableOrDelete($model)
    {
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

            return $model->update($runValidations, $updatedAttributes);

        } else {
            return $model->delete();
        }
    }
    
    /**
     * @inheritdoc
     */
    protected function loadAndSaveModel(ActiveRecord $model, $data = null, $redirect = ['index'])
    {
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
    
    /**
     * @return \app\components\ActiveRecord
     */
    protected function buildNewModel()
    {
        $class = $this->getModelClassName();
        return new $class;
    }

    /**
     * Nome do método chamado no modelo para salvá-lo/enviá-lo/escrevê-lo/etc.
     * @return string
     */
    protected function getModelSaveMethodName()
    {
        return 'save';
    }

    /**
     * @inheritdoc
     */
    public function renderAjaxOrLayout($view, $params = [])
    {
        if (Yii::$app->request->isAjax) {
            return Alert::widget() . parent::renderPartial($view, $params);
        } else {
            return parent::render($view, $params);
        }
    }
}
