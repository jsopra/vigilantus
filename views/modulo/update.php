<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Modulo $model
 */

$this->title = 'Atualizar Módulo: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Módulos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="modulo-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
