<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

echo "<?php\n";
?>

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var <?= ltrim($generator->modelClass, '\\') ?> $model
 */

$this->title = 'Cadastrar <?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>';
$this->params['breadcrumbs'][] = ['label' => '<?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">
	<h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
	<?= "<?php " ?>echo $this->render('_form', ['model' => $model]); ?>
</div>
