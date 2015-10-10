<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Municipio $model
 */

$this->title = 'Atualizar Município: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Municípios', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="municipio-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_form', ['model' => $model]); ?>
</div>
