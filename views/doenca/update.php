<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Doenca $model
 */

$this->title = 'Atualizar Doença: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Doencas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="doenca-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
