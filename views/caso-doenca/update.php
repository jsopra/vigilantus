<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\CasoDoenca $model
 */

$this->title = 'Atualizar Caso de Doença: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Caso de Doença', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="caso-doenca-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
