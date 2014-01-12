<?php
namespace app\components;

use Yii;
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
        $class = $this->getModelClassName();
        $model = new $class;
        
        if ($this->loadAndSaveModel($model, $_POST)) {
            Yii::$app->session->setFlash('success', $this->createFlashMessage);
        } else {
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

        if ($this->loadAndSaveModel($model, $_POST)) {
            Yii::$app->session->setFlash('success', $this->updateFlashMessage);
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
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
}
