<?php
namespace app\extensions\gii\generators\model;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\base\NotSupportedException;
use yii\gii\generators\model\Generator as YiiModelGenerator;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends YiiModelGenerator
{
	public $ns = 'app';
	public $baseClass = 'app\components\ActiveRecord';
    
    public $generateModelQuery = true;
    public $generateFixtures = true;
    public $generateUnit = true;

	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return 'Gerador de Modelo Vigilantus';
	}

	/**
	 * @inheritdoc
	 */
	public function getDescription()
	{
		return 'Gera uma ActiveRecord class para uma tabela no padrão Vigilantus';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return array_merge(parent::rules(), [
			[['db', 'ns', 'tableName', 'modelClass', 'baseClass'], 'filter', 'filter' => 'trim'],
			[['db', 'ns', 'tableName', 'baseClass'], 'required'],
			[['db', 'modelClass'], 'match', 'pattern' => '/^\w+$/', 'message' => 'Only word characters are allowed.'],
			[['ns', 'baseClass'], 'match', 'pattern' => '/^[\w\\\\]+$/', 'message' => 'Only word characters and backslashes are allowed.'],
			[['tableName'], 'match', 'pattern' => '/^(\w+\.)?([\w\*]+)$/', 'message' => 'Only word characters, and optionally an asterisk and/or a dot are allowed.'],
			[['db'], 'validateDb'],
			[['ns'], 'validateNamespace'],
			[['tableName'], 'validateTableName'],
			[['modelClass'], 'validateModelClass', 'skipOnEmpty' => false],
			[['baseClass'], 'validateClass', 'params' => ['extends' => ActiveRecord::className()]],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'db' => 'Database Connection ID',
			'tableName' => 'Table Name',
			'modelClass' => 'Model Class',
			'baseClass' => 'Base Class',
			'generateRelations' => 'Generate Relations',
			'generateLabelsFromComments' => 'Generate Labels from DB Comments',
            'generateModelQuery' => 'Generate Query Class',
            'generateFixtures' => 'Generate Fixtures',
            'generateUnit' => 'Generate Unit tests'
		];
	}

	/**
	 * @inheritdoc
	 */
	public function hints()
	{
		return [
			'db' => 'This is the ID of the DB application component.',
			'tableName' => 'This is the name of the DB table that the new ActiveRecord class is associated with, e.g. <code>tbl_post</code>.
				The table name may consist of the DB schema part if needed, e.g. <code>public.tbl_post</code>.
				The table name may end with asterisk to match multiple table names, e.g. <code>tbl_*</code>
				will match tables who name starts with <code>tbl_</code>. In this case, multiple ActiveRecord classes
				will be generated, one for each matching table name; and the class names will be generated from
				the matching characters. For example, table <code>tbl_post</code> will generate <code>Post</code>
				class.',
			'modelClass' => 'This is the name of the ActiveRecord class to be generated. The class name should not contain
				the namespace part as it is specified in "Namespace". You do not need to specify the class name
				if "Table Name" ends with asterisk, in which case multiple ActiveRecord classes will be generated.',
			'baseClass' => 'This is the base class of the new ActiveRecord class. It should be a fully qualified namespaced class name.',
			'generateRelations' => 'This indicates whether the generator should generate relations based on
				foreign key constraints it detects in the database. Note that if your database contains too many tables,
				you may want to uncheck this option to accelerate the code generation process.',
			'generateLabelsFromComments' => 'This indicates whether the generator should generate attribute labels
				by using the comments of the corresponding DB columns.',
            'generateModelQuery' => 'This indicates whether the generator should generate model query class, e.g., <code>app\models\query</code>',
            'generateFixtures' => 'This indicates whether the generator should generate fixtures for this class, e.g., <code>app\tests\fixtures</code>',
            'generateUnit' => 'This indicates whether the generator should generate unit test class, e.g., <code>app\tests\unit\models</code>',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function requiredTemplates()
	{
		return ['model.php', 'query.php', 'fixtures.php', 'unit.php'];
	}

	/**
	 * @inheritdoc
	 */
	public function stickyAttributes()
	{
		return ['ns', 'db', 'baseClass', 'generateRelations', 'generateLabelsFromComments', 'generateModelQuery', 'generateFixtures', 'generateUnit'];
	}

	/**
	 * @inheritdoc
	 */
	public function generate()
	{
		$files = [];
		$relations = $this->generateRelations();
		$db = $this->getDbConnection();
		foreach ($this->getTableNames() as $tableName) {
			$className = $this->generateClassName($tableName);
			$tableSchema = $db->getTableSchema($tableName);
			$params = [
				'tableName' => $tableName,
				'className' => $className,
				'tableSchema' => $tableSchema,
				'labels' => $this->generateLabels($tableSchema),
				'rules' => $this->generateRules($tableSchema),
				'relations' => isset($relations[$className]) ? $relations[$className] : [],
			];
			$files[] = new CodeFile(
				Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/models/' . $className . '.php',
				$this->render('model.php', $params)
			);
            
            if($this->generateModelQuery) {
                $files[] = new CodeFile(
                    Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/models/query/' . $className . 'Query.php',
                    $this->render('query.php', $params)
                );
            }

            if($this->generateFixtures) {
                $files[] = new CodeFile(
                    Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/tests/fixtures/' . $tableName . '.php',
                    $this->render('fixtures.php', $params)
                );
            }
            
            if($this->generateUnit) {
                $files[] = new CodeFile(
                    Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/tests/unit/models/' . $className . 'Test.php',
                    $this->render('unit.php', $params)
                );
            }
		}

		return $files;
	}
}
