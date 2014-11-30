<?php

use yii\helpers\StringHelper;

/**
 * This is the template for generating a CRUD controller class file.
 *
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use app\components\SearchModel;

/**
 * <?= $searchModelClass ?> represents the model behind the search form about <?= $modelClass ?>.
 */
class <?= $searchModelClass ?> extends SearchModel
{
	public $<?= implode(";\n\tpublic $", $searchAttributes) ?>;

	public function rules()
	{
		return [
			<?= implode(",\n\t\t\t", $rules) ?>,
		];
	}

	public function searchConditions($query)
	{
		<?= implode("\n\t\t", $searchConditions) ?>
        <?= "\n" ?>
	}
}
