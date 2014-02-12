<?php
namespace app\extensions\gii\generators\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\web\Controller;
use yii\gii\generators\crud\Generator as YiiCrudGenerator;

class Generator extends YiiCrudGenerator
{
	public $baseControllerClass = 'app\components\Controller';
	public $indexWidgetType = 'grid';
    public $acceptanceClass;

	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return 'Gerador de CRUD Vigilantus';
	}

	/**
	 * @inheritdoc
	 */
	public function getDescription()
	{
		return 'Gera um controller e views que implementam um CRUD (operações Create, Read, Update, Delete) para o modelo especificado.';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return array_merge(parent::rules(), [
			[['moduleID', 'controllerClass', 'modelClass', 'searchModelClass', 'baseControllerClass', 'acceptanceClass'], 'filter', 'filter' => 'trim'],
			[['modelClass', 'searchModelClass', 'controllerClass', 'baseControllerClass', 'indexWidgetType'], 'required'],
			[['searchModelClass'], 'compare', 'compareAttribute' => 'modelClass', 'operator' => '!==', 'message' => 'Search Model Class must not be equal to Model Class.'],
			[['acceptanceClass'], 'compare', 'compareAttribute' => 'modelClass', 'operator' => '!==', 'message' => 'Acceptance Test Class must not be equal to Model Class.'],
            [['modelClass', 'controllerClass', 'baseControllerClass', 'searchModelClass', 'acceptanceClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
			[['modelClass'], 'validateClass', 'params' => ['extends' => BaseActiveRecord::className()]],
			[['baseControllerClass'], 'validateClass', 'params' => ['extends' => Controller::className()]],
			[['controllerClass'], 'match', 'pattern' => '/Controller$/', 'message' => 'Controller class name must be suffixed with "Controller".'],
			[['controllerClass', 'searchModelClass'], 'validateNewClass'],
			[['indexWidgetType'], 'in', 'range' => ['grid', 'list']],
			[['modelClass'], 'validateModelClass'],
			[['moduleID'], 'validateModuleID'],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), [
			'modelClass' => 'Model Class',
			'moduleID' => 'Module ID',
			'controllerClass' => 'Controller Class',
			'baseControllerClass' => 'Base Controller Class',
			'indexWidgetType' => 'Widget Used in Index Page',
			'searchModelClass' => 'Search Model Class',
            'acceptanceClass' => 'Acceptance Test Class',
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function hints()
	{
		return [
			'modelClass' => 'This is the ActiveRecord class associated with the table that CRUD will be built upon.
				You should provide a fully qualified class name, e.g., <code>app\models\Post</code>.',
			'controllerClass' => 'This is the name of the controller class to be generated. You should
				provide a fully qualified namespaced class, .e.g, <code>app\controllers\PostController</code>.',
			'baseControllerClass' => 'This is the class that the new CRUD controller class will extend from.
				You should provide a fully qualified class name, e.g., <code>yii\web\Controller</code>.',
			'moduleID' => 'This is the ID of the module that the generated controller will belong to.
				If not set, it means the controller will belong to the application.',
			'indexWidgetType' => 'This is the widget type to be used in the index page to display list of the models.
				You may choose either <code>GridView</code> or <code>ListView</code>',
			'searchModelClass' => 'This is the class representing the data being collected in the search form.
			 	A fully qualified namespaced class name is required, e.g., <code>app\models\search\PostSearch</code>.',
            'acceptanceClass' => 'This is the class representing the data being collected in the search form.
			 	A fully qualified namespaced class name is required, e.g., <code>app\tests\acceptance\cadastro\PostCept</code>.',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function requiredTemplates()
	{
		return ['controller.php', 'search.php', 'acceptance.php'];
	}

	/**
	 * @inheritdoc
	 */
	public function stickyAttributes()
	{
		return ['baseControllerClass', 'moduleID', 'indexWidgetType'];
	}

	/**
	 * @inheritdoc
	 */
	public function generate()
	{
		$controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');
		$searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));
		$files = [
			new CodeFile($controllerFile, $this->render('controller.php')),
			new CodeFile($searchModel, $this->render('search.php')),
		];
        
        if($this->acceptanceClass) {
            $acceptanceFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->acceptanceClass, '\\') . '.php'));
            $files[] = new CodeFile($acceptanceFile, $this->render('acceptance.php'));
        }
        
        $viewPath = $this->getViewPath();
		$templatePath = $this->getTemplatePath() . '/views';
		foreach (scandir($templatePath) as $file) {
			if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
				$files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
			}
		}
        
		return $files;
	}
}