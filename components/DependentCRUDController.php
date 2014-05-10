<?php
namespace app\components;

use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\CRUDController;

class DependentCRUDController extends CRUDController
{
    /**
     * Modelo que este crud depende
     * @var string
     */
    protected $dependentModel;
    
    /**
     * ID do modelo que este crud depende
     * @var int 
     */
    protected $dependentID;
    
    /**
     * Objeto do modelo carregado através do ID
     * @var Object 
     */
    protected $parentObject;
    
    /**
     * Campo do modelo dependente que representa o modelo pai (ex: bairro_id no modelo BairroQuarteirao)
     * @var string 
     */
    protected $parentField;
    
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
                        'matchCallback' => function ($rule, $action) {
                            return isset($_GET['parentID']) && is_numeric($_GET['parentID']);
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'matchCallback' => function ($rule, $action) {
                        return isset($_GET['parentID']) && is_numeric($_GET['parentID']);
                    }
                ],
            ],
        ];
    }
    
    public function init()
    {
        if(!$this->dependentModel || !$this->parentField) {
            Yii::$app->session->setFlash('error', 'Modelo dependente não foi corretamente definido');
            return $this->redirect(['site/home']);
        }
            
        $this->dependentID = $_GET['parentID'];
        $class = 'app\\models\\' . $this->dependentModel;
        $dependentModel = new $class;
        $this->parentObject = $dependentModel->findOne($this->dependentID);

        if(!$this->parentObject instanceof $class) {
            Yii::$app->session->setFlash('error', 'Modelo dependente não foi carregado');
            return $this->redirect(['site/home']);
        }
        
        return parent::init();
    }
    
    public function actionIndex()
    {
        $_GET[$this->parentField] = $this->parentObject->id;
        
        $searchModelClass = $this->getSearchModelClassName();
        $searchModel = new $searchModelClass;
        $dataProvider = $searchModel->search($_GET);
        
        return $this->render(
            'index',
            [
                'searchModel' => $searchModel, 
                'dataProvider' => $dataProvider,
                'parentObject' => $this->parentObject,
            ]
        );
    }
    
    public function actionCreate()
    {
        $model = $this->buildNewModel();
        
        $parentField = $this->parentField;
        $model->$parentField = $this->parentObject->id;
        //$model->scenario = 'insert';
        if (!$this->loadAndSaveModel($model, $_POST, ['index', 'parentID' => $this->dependentID])) { 
            return $this->renderAjaxOrLayout('create', ['model' => $model, 'parentObject' => $this->parentObject]);
        }
    }

    public function actionUpdate($id)
    {
        $model = is_object($id) ? $id : $this->findModel($id);
        $parentField = $this->parentField;
        $model->$parentField = $this->parentObject->id;
        //$model->scenario = 'update';

        if (!$this->loadAndSaveModel($model, $_POST, ['index', 'parentID' => $this->dependentID])) { 
            return $this->renderAjaxOrLayout('update', ['model' => $model, 'parentObject' => $this->parentObject]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $this->disableOrDelete($model);

        if (!Yii::$app->request->isAjax)
            Yii::$app->session->setFlash('success', $this->deleteFlashMessage);

        $parentField = $this->parentField;

        return $this->redirect([
            'index',
            'parentID' => $model->$parentField
        ]);
    }
}
